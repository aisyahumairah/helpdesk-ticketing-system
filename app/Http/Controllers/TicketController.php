<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Code;
use App\Models\UploadedFile;
use App\Models\AuditTrail;
use App\Notifications\TicketStatusUpdated;
use App\Notifications\TicketAssigned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->with(['categoryCode', 'urgencyCode', 'statusCode'])
            ->latest()
            ->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Code::where('type', 'comp_type')->get();
        $urgencies = Code::where('type', 'urgency')->get();
        
        $users = null;
        if (Auth::user()->hasAnyRole(['admin', 'it_support'])) {
            $users = \App\Models\User::all();
        }

        return view('tickets.create', compact('categories', 'urgencies', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|exists:codes,code',
            'urgency' => 'required|exists:codes,code',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,mov,avi|max:10240',
        ];

        if (Auth::user()->hasAnyRole(['admin', 'it_support'])) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        // Generate Ticket ID: ID00001/YYYY
        $year = date('Y');
        $lastTicket = Ticket::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastTicket) {
            $ticketIdParts = explode('/', $lastTicket->ticket_id);
            if (count($ticketIdParts) > 0) {
                // Strip "ID" and convert to int
                $lastIdStr = str_replace('ID', '', $ticketIdParts[0]);
                $lastSequence = (int)$lastIdStr;
                $sequence = $lastSequence + 1;
            }
        }
        $ticketId = 'ID' . str_pad($sequence, 5, '0', STR_PAD_LEFT) . '/' . $year;

        $ticket = Ticket::create([
            'ticket_id' => $ticketId,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'urgency' => $request->urgency,
            'status' => 'NEW',
            'user_id' => $request->user_id ?? Auth::id(),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/' . $ticketId, 'public');
                $ticket->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'filetype' => $file->getClientMimeType(),
                ]);
            }
        }

        \App\Models\AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Create',
            'details' => 'Ticket created by ' . Auth::user()->name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully with ID: ' . $ticketId);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin', 'it_support'])) {
            abort(403);
        }

        $ticket->load([
            'categoryCode', 'urgencyCode', 'statusCode', 'attachments', 'user', 'assignedTo', 
            'auditTrails.user', 'replies.user', 'replies.attachments'
        ]);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Users can't edit tickets after creation normally, but we can implement it if needed.
        abort(404);
    }

    /**
     * Self-assign a ticket to the authenticated support staff.
     */
    public function assign(Ticket $ticket)
    {
        if ($ticket->assigned_to) {
            return redirect()->back()->with('error', 'Ticket is already assigned.');
        }

        $ticket->update([
            'assigned_to' => Auth::id(),
            'status' => 'PEND', // Change to Pending/In Progress
        ]);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Assign',
            'details' => 'Ticket assigned to ' . Auth::user()->name,
            'ip_address' => request()->ip(),
        ]);

        // Send Notification
        $ticket->user->notify(new TicketAssigned($ticket));

        return redirect()->route('support.dashboard')->with('success', 'Ticket assigned to you successfully.');
    }

    /**
     * Resolve/Close a ticket.
     */
    public function resolve(Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update([
            'status' => 'DONE',
        ]);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Resolve',
            'details' => 'Ticket resolved by ' . Auth::user()->name,
            'ip_address' => request()->ip(),
        ]);

        // Send Notification
        $ticket->user->notify(new TicketStatusUpdated($ticket));

        return redirect()->route('support.dashboard')->with('success', 'Ticket marked as resolved.');
    }

    /**
     * Escalate a ticket.
     */
    public function escalate(Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $ticket->update([
            'assigned_to' => null,
            'status' => 'PEND', // Keep it pending but back in pool
            'escalation_level' => $ticket->escalation_level + 1,
        ]);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Escalate',
            'details' => 'Ticket escalated to level ' . $ticket->escalation_level . ' by ' . Auth::user()->name,
            'ip_address' => request()->ip(),
        ]);

        // Send Notification
        $ticket->user->notify(new TicketStatusUpdated($ticket));

        return redirect()->route('support.dashboard')->with('success', 'Ticket escalated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Implementation for status updates, verifying, reopening
        // This will be expanded in Phase 2.2/2.3
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
