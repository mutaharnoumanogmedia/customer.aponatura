<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PortalNotification extends Notification
{
    use Queueable;

    public $title;
    public $link;
    public $data;
    public $notifiable_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $notifiable_id, string $title,  string $data, string $link = '')
    {
        $this->title = $title;
        $this->link = $link;
        $this->data = $data;
        $this->notifiable_id = $notifiable_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'link' => $this->link,
            'data' => $this->data,
            'created_at' => now(),
            'read_at' => null,
            'notifiable_id' => $this->notifiable_id,
            'notifiable_type' => get_class($notifiable),
            'type' => self::class,
        ];
    }
}
