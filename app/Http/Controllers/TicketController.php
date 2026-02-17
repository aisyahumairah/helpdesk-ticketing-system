<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Code;
use App\Models\UploadedFile;
use App\Models\AuditTrail;
use App\Models\TicketStatusHistory;
use App\Notifications\TicketStatusUpdated;
use App\Notifications\TicketAssigned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

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

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Create',
            'details' => [
                'message' => 'Ticket created by ' . Auth::user()->name,
                'data' => [
                    'title' => $ticket->title,
                    'category' => $ticket->category,
                    'urgency' => $ticket->urgency,
                    'status' => $ticket->status,
                ]
            ],
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
            'categoryCode',
            'urgencyCode',
            'statusCode',
            'attachments',
            'user',
            'assignedTo',
            'auditTrails.user',
            'replies.user',
            'replies.attachments'
        ]);

        $supportUsers = \App\Models\User::role(['admin', 'it_support'])->get();

        return view('tickets.show', compact('ticket', 'supportUsers'));
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
            'details' => [
                'message' => 'Ticket self-assigned by ' . Auth::user()->name,
                'changes' => [
                    'assigned_to' => ['old' => null, 'new' => Auth::id()],
                    'status' => ['old' => 'NEW', 'new' => 'PEND']
                ]
            ],
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
        if ($ticket->assigned_to !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $oldStatus = $ticket->status;

        try {
            $ticket->update([
                'status' => 'CLOSE',
                'resolved_at' => now(),
                'resolved_by' => Auth::id(),
            ]);

            // Record status change
            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => 'CLOSE',
                'changed_by' => Auth::id(),
                'notes' => 'Ticket resolved by IT Support',
            ]);

            AuditTrail::create([
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'event' => 'Resolve',
                'details' => [
                    'message' => 'Ticket resolved by ' . Auth::user()->name,
                    'changes' => [
                        'status' => ['old' => $oldStatus, 'new' => 'CLOSE']
                    ]
                ],
                'ip_address' => request()->ip(),
            ]);

            // Send Notification to user
            $ticket->user->notify(new TicketStatusUpdated($ticket));

            return redirect()->back()->with('success', 'Ticket marked as resolved. User will be notified to verify.');
        } catch (Throwable $e) {
            Log::error('Failed to resolve ticket: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to resolve ticket: ' . $e->getMessage());
        }
    }

    /**
     * User verifies the ticket resolution
     */
    public function verify(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Only the ticket creator can verify resolution.');
        }

        if ($ticket->status !== 'CLOSE') {
            return redirect()->back()->with('error', 'Ticket must be in CLOSE status to verify.');
        }

        $oldStatus = $ticket->status;

        try {
            $ticket->update([
                'status' => 'DONE',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Record status change
            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => 'DONE',
                'changed_by' => Auth::id(),
                'notes' => 'Ticket verified as resolved by user',
            ]);

            AuditTrail::create([
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'event' => 'Verify',
                'details' => [
                    'message' => 'Ticket verified as resolved by ' . Auth::user()->name,
                    'changes' => [
                        'status' => ['old' => $oldStatus, 'new' => 'DONE']
                    ]
                ],
                'ip_address' => request()->ip(),
            ]);

            // Notify IT Support
            if ($ticket->assignedTo) {
                $ticket->assignedTo->notify(new TicketStatusUpdated($ticket));
            }

            return response()->json(['message' => 'Thank you for verifying the resolution!'], 200);
        } catch (Throwable $e) {
            Log::error('Failed to verify ticket: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to verify ticket: ' . $e->getMessage()], 500);
        }
    }

    /**
     * User reopens a ticket
     */
    public function reopen(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Only the ticket creator can reopen the ticket.');
        }

        if (!in_array($ticket->status, ['CLOSE', 'DONE'])) {
            return redirect()->back()->with('error', 'Only closed or done tickets can be reopened.');
        }

        $oldStatus = $ticket->status;

        try {
            $ticket->update([
                'status' => 'REOPEN',
                'reopen_count' => $ticket->reopen_count + 1,
            ]);

            // Record status change
            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'old_status' => $oldStatus,
                'new_status' => 'REOPEN',
                'changed_by' => Auth::id(),
                'notes' => 'Ticket reopened by user (Reopen #' . $ticket->reopen_count . ')',
            ]);

            AuditTrail::create([
                'user_id' => Auth::id(),
                'ticket_id' => $ticket->id,
                'event' => 'Reopen',
                'details' => [
                    'message' => 'Ticket reopened by ' . Auth::user()->name . ' (Reopen #' . $ticket->reopen_count . ')',
                    'changes' => [
                        'status' => ['old' => $oldStatus, 'new' => 'REOPEN'],
                        'reopen_count' => ['old' => $ticket->reopen_count - 1, 'new' => $ticket->reopen_count]
                    ]
                ],
                'ip_address' => request()->ip(),
            ]);

            // Notify assigned IT Support
            if ($ticket->assignedTo) {
                $ticket->assignedTo->notify(new TicketStatusUpdated($ticket));
            }

            return response()->json(['message' => 'Ticket has been reopened. IT Support will be notified.'], 200);
        } catch (Throwable $e) {
            Log::error('Failed to reopen ticket: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to reopen ticket: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reassign ticket to another IT support
     */
    public function reassign(Request $request, Ticket $ticket)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_support'])) {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldAssignee = $ticket->assigned_to;
        $newAssignee = $request->assigned_to;

        if ($oldAssignee == $newAssignee) {
            return redirect()->back()->with('error', 'Ticket is already assigned to this user.');
        }

        // Record the assignment
        \App\Models\TicketAssignment::create([
            'ticket_id' => $ticket->id,
            'assigned_from' => $oldAssignee,
            'assigned_to' => $newAssignee,
            'notes' => $request->notes,
        ]);

        $ticket->update([
            'assigned_to' => $newAssignee,
            'status' => 'PEND', // Keep it pending
        ]);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Reassign',
            'details' => [
                'message' => 'Ticket reassigned from ' . ($oldAssignee ? \App\Models\User::find($oldAssignee)->name : 'Unassigned') . ' to ' . \App\Models\User::find($newAssignee)->name,
                'changes' => [
                    'assigned_to' => ['old' => $oldAssignee, 'new' => $newAssignee],
                    'notes' => ['old' => null, 'new' => $request->notes]
                ]
            ],
            'ip_address' => request()->ip(),
        ]);

        // Notify new assignee
        $newAssigneeUser = \App\Models\User::find($newAssignee);
        $newAssigneeUser->notify(new TicketAssigned($ticket));

        return redirect()->back()->with('success', 'Ticket reassigned successfully.');
    }

    /**
     * Escalate a ticket.
     */
    public function escalate(Request $request, Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldAssignee = $ticket->assigned_to;
        $newAssignee = $request->assigned_to;

        // Record the assignment/escalation
        \App\Models\TicketAssignment::create([
            'ticket_id' => $ticket->id,
            'assigned_from' => $oldAssignee,
            'assigned_to' => $newAssignee,
            'notes' => $request->notes ?? 'Ticket escalated',
        ]);

        $ticket->update([
            'assigned_to' => $newAssignee,
            'status' => 'PEND',
            'escalation_level' => $ticket->escalation_level + 1,
        ]);

        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Escalate',
            'details' => [
                'message' => 'Ticket escalated from ' . ($oldAssignee ? \App\Models\User::find($oldAssignee)->name : 'Unassigned') . ' to ' . \App\Models\User::find($newAssignee)->name,
                'changes' => [
                    'assigned_to' => ['old' => $oldAssignee, 'new' => $newAssignee],
                    'escalation_level' => ['old' => $ticket->escalation_level - 1, 'new' => $ticket->escalation_level],
                    'notes' => ['old' => null, 'new' => $request->notes]
                ]
            ],
            'ip_address' => request()->ip(),
        ]);

        // Send Notification
        $ticket->user->notify(new TicketStatusUpdated($ticket));

        // Notify new assignee
        $newAssigneeUser = \App\Models\User::find($newAssignee);
        $newAssigneeUser->notify(new TicketAssigned($ticket));

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
