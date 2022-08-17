<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimeOffNotifications extends Notification
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
        return ['database'];
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
