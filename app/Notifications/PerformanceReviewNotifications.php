<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerformanceReviewNotifications extends Notification
{
    use Queueable;
    protected $employeeData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($employeeData)
    {
        //
        $this->data = $employeeData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Performance Review Notifications')
                    ->action('Performance Review', url('/en/performance-review'))
                    ->line('Thank you for using our Hr Portal!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['description'],
            'description' => null,
            'url' => $this->data['url'],
        ];
    }
}
