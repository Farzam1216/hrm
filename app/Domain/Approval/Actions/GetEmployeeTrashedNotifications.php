<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\Auth;

class GetEmployeeTrashedNotifications
{
    /**
     * get trashed notifications of logged user.
     *
     * @return Employee
     */
    public function execute()
    {
        return Employee::with([
            'notifications' => function ($query) {
                $query->where('deleted_at', '!=', null)->get();
            }
        ])->where('id', Auth::user()->id)->first();
    }
}
