<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $ticketId, public int $messageId)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ticket.' . $this->ticketId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message-deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket_id'  => $this->ticketId,
            'message_id' => $this->messageId,
        ];
    }
}
