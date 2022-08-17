<?php


namespace App\Domain\Approval\Actions;

use Illuminate\Support\Facades\Auth;

class ShowInbox
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute($id)
    {
        $notifications = Auth::user()->notifications;
        $data['notifications'] = $notifications->where('id', $id)->first();
        $data['notificationCount'] = (new GetEmployeeNotifications())->execute()->notifications->count();
        $data['trashedNotifications']= (new GetEmployeeTrashedNotifications())->execute()->notifications->count();
        $data['completedNotifications']= (new GetEmployeeCompletedNotifications())->execute()->notifications->count();
        return $data;
    }
}
