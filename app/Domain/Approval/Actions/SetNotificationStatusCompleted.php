<?php


namespace App\Domain\Approval\Actions;

use App\Models\Notification;

class SetNotificationStatusCompleted
{
    /**
     * @param $notificationId
     */
    public function execute($notificationId)
    {
        $notification = Notification::find($notificationId);
        $notification->is_completed = true;
        $notification->save();
    }
}
