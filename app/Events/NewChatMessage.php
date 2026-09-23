<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $chatMessage)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ticket.' . $this->chatMessage->ticket_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }

    public function broadcastWith(): array
    {
        $payload = [
            'id'         => $this->chatMessage->id,
            'message'    => $this->chatMessage->message,
            'user_id'    => $this->chatMessage->user_id,
            'user_name'  => $this->chatMessage->user->name,
            'is_read'    => $this->chatMessage->is_read,
            'reply_to_id'=> $this->chatMessage->reply_to_id,
            'created_at' => $this->chatMessage->created_at->toISOString(),
            'timestamp'  => $this->chatMessage->created_at->format('H:i'),
        ];

        if ($this->chatMessage->attachments && $this->chatMessage->attachments->count() > 0) {
            $payload['attachments'] = $this->chatMessage->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'url' => \Illuminate\Support\Facades\Storage::url($attachment->filepath),
                    'filename' => $attachment->filename,
                    'filetype' => $attachment->filetype,
                ];
            })->toArray();
        }

        if ($this->chatMessage->reply_to_id) {
            $replyTo = $this->chatMessage->replyTo;
            if ($replyTo) {
                $payload['reply_message'] = \Illuminate\Support\Str::limit($replyTo->message, 50);
                $payload['reply_user_name'] = $replyTo->user->name;
            }
        }

        return $payload;
    }
}
