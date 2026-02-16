<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Reply;

class NewTicketReply extends Notification
{
    use Queueable;

    protected $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
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
            ->subject('New Reply on Ticket: ' . $this->reply->ticket->ticket_id)
            ->line($this->reply->user->name . ' has added a new reply to the ticket "' . $this->reply->ticket->title . '".')
            ->line('"' . str()->limit($this->reply->message, 100) . '"')
            ->action('View Ticket', route('tickets.show', $this->reply->ticket))
            ->line('Thank you for using our helpdesk!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->reply->ticket->id,
            'ticket_code' => $this->reply->ticket->ticket_id,
            'user_name' => $this->reply->user->name,
            'message' => 'New reply from ' . $this->reply->user->name,
            'type' => 'new_reply'
        ];
    }
}
