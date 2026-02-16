<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Ticket;

class TicketAssigned extends Notification
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (\App\Models\SystemSetting::where('key', 'email_enabled')->value('value') !== '0') {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Assigned: ' . $this->ticket->ticket_id)
            ->line('Your ticket "' . $this->ticket->title . '" has been assigned to ' . ($this->ticket->assignedTo->name ?? 'a support staff member') . '.')
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('A support staff member will assist you shortly.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_id,
            'title' => $this->ticket->title,
            'assigned_to' => $this->ticket->assignedTo->name ?? 'Unknown',
            'message' => 'Ticket assigned to ' . ($this->ticket->assignedTo->name ?? 'Support Staff'),
            'type' => 'ticket_assigned'
        ];
    }
}
