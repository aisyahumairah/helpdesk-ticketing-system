<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Events\ChatMessageRead;
use App\Events\ChatMessageDeleted;
use App\Events\NewNotificationBroadcast;
use App\Models\ChatMessage;
use App\Models\Ticket;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index(Ticket $ticket)
    {
        // Authorize access
        if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole(['admin', 'it_support'])) {
            abort(403, 'Unauthorized access to this chat.');
        }

        $messages = ChatMessage::with(['user', 'replyTo.user', 'attachments'])
            ->where('ticket_id', $ticket->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $unreadCount = ChatMessage::where('ticket_id', $ticket->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        if ($unreadCount > 0) {
            broadcast(new ChatMessageRead($ticket->id, Auth::id()))->toOthers();
        }

        return view('tickets.chat', compact('ticket', 'messages'));
    }

    public function store(Request $request, Ticket $ticket)
    {
        // Authorize access
        if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole(['admin', 'it_support'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $hasFiles = $request->hasFile('attachments');
        $request->validate([
            'message' => $hasFiles ? 'nullable|string|max:1000' : 'required|string|max:1000',
            'reply_to_id' => 'nullable|exists:chat_messages,id',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240' // max 10MB per file
        ]);

        $message = ChatMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'reply_to_id' => $request->reply_to_id,
        ]);

        if ($hasFiles) {
            foreach ($request->file('attachments') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $filepath = $file->storeAs('chat_attachments', $filename, 'public');

                $message->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'filepath' => $filepath,
                    'filetype' => $file->getClientMimeType(),
                ]);
            }
        }
        
        $message->load(['user', 'attachments', 'replyTo.user']);

        // Broadcast the new message event
        broadcast(new NewChatMessage($message))->toOthers();

        // Broadcast notification to other participants
        $this->notifyParticipants($ticket, $message);

        return response()->json([
            'status' => 'success',
            'message' => $message->load('user'),
            'timestamp' => $message->created_at->format('H:i')
        ]);
    }

    public function markRead(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole(['admin', 'it_support'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unreadCount = ChatMessage::where('ticket_id', $ticket->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        if ($unreadCount > 0) {
            broadcast(new ChatMessageRead($ticket->id, Auth::id()))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Request $request, Ticket $ticket, ChatMessage $chatMessage)
    {
        if ($chatMessage->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messageId = $chatMessage->id;

        // Delete attached files from storage
        foreach ($chatMessage->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->filepath);
            $attachment->delete();
        }

        $chatMessage->delete();

        broadcast(new ChatMessageDeleted($ticket->id, $messageId))->toOthers();

        return response()->json(['status' => 'success']);
    }

    public function destroyAll(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole(['admin', 'it_support'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Determine if they want to clear their own messages or all
        $messages = ChatMessage::where('ticket_id', $ticket->id)
            ->where('user_id', Auth::id())
            ->get();
            
        foreach ($messages as $msg) {
            // Delete attached files from storage
            foreach ($msg->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->filepath);
                $attachment->delete();
            }
            $msg->delete();
            broadcast(new ChatMessageDeleted($ticket->id, $msg->id))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    private function notifyParticipants(Ticket $ticket, ChatMessage $message)
    {
        $senderId = Auth::id();
        $senderName = Auth::user()->name;
        
        $recipients = [];

        // If sender is NOT the ticket owner, notify the owner
        if ($senderId !== $ticket->user_id) {
            $recipients[] = $ticket->user_id;
        }

        // If sender is NOT the assigned IT support, notify them
        if ($ticket->assigned_to && $senderId !== $ticket->assigned_to) {
            $recipients[] = $ticket->assigned_to;
        }

        $recipients = array_unique($recipients);

        foreach ($recipients as $recipientId) {
            broadcast(new NewNotificationBroadcast(
                userId: $recipientId,
                title: 'New Chat Message',
                message: "$senderName sent a message: " . \Illuminate\Support\Str::limit($message->message, 50),
                ticketCode: $ticket->ticket_id,
                ticketId: $ticket->id,
                type: 'chat'
            ))->toOthers();
        }
    }

    public function unreadCount(Request $request)
    {
        $unreadMessages = ChatMessage::with(['ticket', 'user'])
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->whereHas('ticket', function ($query) {
                if (!Auth::user()->hasRole(['admin', 'it_support'])) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $count = $unreadMessages->count();

        // Group by ticket
        $grouped = $unreadMessages->groupBy('ticket_id')->map(function ($messages) {
            $latest = $messages->first();
            $msgText = $latest->message ? \Illuminate\Support\Str::limit($latest->message, 40) : 'Sent an attachment';
            
            $isSupport = Auth::user()->hasRole(['admin', 'it_support']);
            $url = $isSupport ? route('support.adminshow', $latest->ticket_id) : route('tickets.show', $latest->ticket_id);

            return [
                'ticket_id' => $latest->ticket_id,
                'ticket_code' => $latest->ticket->ticket_id,
                'sender_name' => $latest->user->name,
                'message' => $msgText,
                'time' => $latest->created_at->diffForHumans(null, true),
                'unread_count' => $messages->count(),
                'url' => $url
            ];
        })->values();

        return response()->json([
            'count' => $count,
            'chats' => $grouped
        ]);
    }
}
