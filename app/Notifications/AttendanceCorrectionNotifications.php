<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceCorrectionNotifications extends Notification
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
                    ->line('Attendance Correction Notifications')
                    ->action('Attendance Correction', url('/en/correction-requests'))
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
            'url' => $this->data['url'],
        ];
    }
}
