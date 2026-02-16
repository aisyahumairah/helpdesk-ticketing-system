<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Ticket;

class TicketStatusUpdated extends Notification
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
            ->subject('Ticket Status Updated: ' . $this->ticket->ticket_id)
            ->line('The status of your ticket "' . $this->ticket->title . '" has been updated to ' . $this->ticket->status . '.')
            ->action('View Ticket', route('tickets.show', $this->ticket))
            ->line('Thank you for using our helpdesk!');
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
            'status' => $this->ticket->status,
            'message' => 'Status updated to ' . $this->ticket->status,
            'type' => 'status_updated'
        ];
    }
}
