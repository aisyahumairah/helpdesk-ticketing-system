<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\Reply;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        // Access Check
        if ($ticket->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin', 'it_support'])) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,mov,avi|max:10240',
        ]);

        $reply = Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('replies/' . $ticket->ticket_id, 'public');
                $reply->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $path,
                    'filetype' => $file->getClientMimeType(),
                ]);
            }
        }

        // Log to Audit Trail
        AuditTrail::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event' => 'Reply',
            'details' => 'New reply from ' . Auth::user()->name,
            'ip_address' => $request->ip(),
        ]);

        // Send Notification
        if (Auth::id() === $ticket->user_id) {
            // If user replied, notify assigned staff
            if ($ticket->assignedTo) {
                $ticket->assignedTo->notify(new \App\Notifications\NewTicketReply($reply));
            }
        } else {
            // If staff replied, notify ticket owner
            $ticket->user->notify(new \App\Notifications\NewTicketReply($reply));
        }

        return redirect()->back()->with('success', 'Reply added successfully.');
    }
}
