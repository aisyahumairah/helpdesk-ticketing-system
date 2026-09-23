<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotificationBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $userId,
        public string $title,
        public string $message,
        public string $ticketCode,
        public int    $ticketId,
        public string $type = 'info'
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'title'       => $this->title,
            'message'     => $this->message,
            'ticket_code' => $this->ticketCode,
            'ticket_id'   => $this->ticketId,
            'type'        => $this->type,
            'timestamp'   => now()->toISOString(),
        ];
    }
}
