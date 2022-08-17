<?php


namespace App\Domain\Approval\Actions;

class ViewInbox
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute($trash)
    {
        if (!$trash) {
            $data['employeeNotifications'] = (new GetEmployeeNotifications())->execute();
            $data['employeeTrashedNotifications'] = (new GetEmployeeTrashedNotifications())->execute();
            $data['notificationCompleted'] = (new GetEmployeeCompletedNotifications)->execute();
            $data['notificationCount'] = $data['employeeNotifications']->notifications->count();
            $data['employeeTrashedCount'] = $data['employeeTrashedNotifications']->notifications->count();
        } else {
            //inverse name
            $data['employeeTrashedNotifications'] = (new GetEmployeeNotifications())->execute();
            $data['employeeNotifications'] = (new GetEmployeeTrashedNotifications())->execute();
            $data['employeeTrashedCount'] = $data['employeeNotifications']->notifications->count();
            $data['notificationCount'] = $data['employeeTrashedNotifications']->notifications->count();
            $data['notificationCompleted'] = (new GetEmployeeCompletedNotifications)->execute();
        }
        return $data;
    }
}
