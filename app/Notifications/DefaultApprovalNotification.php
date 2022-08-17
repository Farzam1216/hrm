<?php

namespace App\Notifications;

use App\Domain\Employee\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DefaultApprovalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *  Note: request detail will hold approve/disapprove link and other detail
     * @param Employee $requester
     * @param array $requestDetail
     * @return void
     */
    public function __construct($requester, array $requestDetail)
    {
        $this->setFullName($requester);
        $this->setRequestDetail($requestDetail);
    }
    // Get/Set requester full name
    private $fullName = null;
    public function setFullName($name)
    {
        $this->fullName = $name->firstname . ' ' . $name->lastname;
    }
    public function getFullName()
    {
        return $this->fullName;
    }
    // Get/Set Approval Type
    protected $requestDetails;
    public function setRequestDetail($approvalType)
    {
        $this->requestDetails = $approvalType;
    }
    public function getRequestDetail()
    {
        return $this->requestDetails;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->getRequestDetail()['title'])->markdown('emails.approval_email', ['data' => $this->getRequestDetail()]);
    }

    /**
     * insert in database representation of the notification.
     *
     * @return
     */
    public function toDatabase()
    {
        return $this->getRequestDetail();
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
            //
        ];
    }
}
