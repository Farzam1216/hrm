<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\DB;

class NoAccessEmployees
{
    public function execute()
    {
        $allEmployees = Employee::all();
        $employeesWithRole = DB::table('model_has_roles')->get();
        $employeesWithRole = Employee::whereIn('id', $employeesWithRole->pluck('model_id'))->get();
        $noAccessEmployees = $allEmployees->diff($employeesWithRole);
        return $noAccessEmployees;
    }
}
