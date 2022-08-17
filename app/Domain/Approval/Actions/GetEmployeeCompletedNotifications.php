<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Auth;

class GetEmployeeCompletedNotifications
{
    /**
     * get completed notifications of logged user.
     *
     * @return Employee
     */
    public function execute()
    {
        return Employee::with([
            'notifications' => function ($query) {
                $query->whereNull('deleted_at')->where('is_completed', true)->get();
            }
        ])->where('id', Auth::user()->id)->first();
    }
}
