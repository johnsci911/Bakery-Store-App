<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreReopenedNotification extends Notification
{
    use Queueable;

    public const TYPE = 'store-reopened';

    protected $openingTime;

    /**
     * Create a new notification instance.
     */
    public function __construct($openingTime)
    {
        $this->openingTime = $openingTime;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('EuroBreads Store Notification!')
            ->line('Our store is re-opening on ' . $this->openingTime)
            ->action('Visit Store', url('/'))
            ->line('Thank you for your patience!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

