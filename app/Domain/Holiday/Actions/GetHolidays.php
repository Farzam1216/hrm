<?php

namespace App\Domain\Holiday\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;

class GetHolidays
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $holidays = Holiday::all();
        $employee = Employee::where('id', Auth::id())->with('employeeHolidays')->first();

        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        
        return $data = [
            'holidays' => $holidays,
            'employee' => $employee,
            'permissions' => $data['permissions']
        ];
    }
}
