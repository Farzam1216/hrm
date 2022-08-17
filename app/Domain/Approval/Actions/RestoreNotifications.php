<?php


namespace App\Domain\Approval\Actions;

use App\Models\Notification;

class RestoreNotifications
{
    /**
     * restore the trashed email from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function execute($id)
    {
        try {
            $notification = Notification::onlyTrashed()->where('id', $id)->restore();

            return 'Notification has been restored';
        } catch (\Throwable $th) {
            return 'Operation failed';
        }
    }
}
