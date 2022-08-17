<?php


namespace App\Domain\Approval\Actions;

class CompletedInbox
{
    /**
     * @return mixed $data
     */
    public function execute()
    {
        $employeeNotifications = (new GetEmployeeNotifications())->execute();
        $data['employeeTrashedNotifications'] = (new GetEmployeeTrashedNotifications())->execute();
        $data['completedNotification'] = (new GetEmployeeCompletedNotifications())->execute()->notifications;
        $data['notificationCount'] = $employeeNotifications->notifications->count();
        $data['TrashedNotificationsCount'] = $data['employeeTrashedNotifications']->notifications->count();
        $data['completedNotificationCount'] = $data['completedNotification']->count();

        return $data;
    }
}
