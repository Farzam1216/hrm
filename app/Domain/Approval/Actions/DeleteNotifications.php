<?php


namespace App\Domain\Approval\Actions;

use App\Models\Notification;

class DeleteNotifications
{
    /**
     * Delete notifications of logged user.
     *
     * @return string
     */
    public function execute($id)
    {
        $notification = Notification::withTrashed()->where('id', $id)->first();
        if ($notification->deleted_at == null) {
            $notification->delete();

            return 'Notification has been trashed';
        } else {
            $notification->forceDelete();

            return 'Notification has been deleted';
        }
    }
}
