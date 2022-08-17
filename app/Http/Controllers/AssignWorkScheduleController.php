<?php

namespace App\Http\Controllers;

use App\Domain\Attendance\Actions\AssignWorkScheduleToEmployees;
use App\Domain\Attendance\Actions\GetAllUsersByWorkSchedule;
use App\Domain\Attendance\Actions\GetAllUsersExceptAdminRole;
use App\Domain\Attendance\Actions\GetWorkSchedule;
use App\Domain\Attendance\Models\WorkSchedule;
use Illuminate\Http\Request;
use App;
use Session;

class AssignWorkScheduleController extends Controller
{
    public function create($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');

        $workSchedule = (new GetWorkSchedule())->execute($id);
        $employees = (new GetAllUsersExceptAdminRole)->execute();
        $assignedEmployeeIDByWorkSchedule = (new GetAllUsersByWorkSchedule())->execute($id);

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/work-schedule", 'name' => "Work Schedules"],
            ['name' => "Assign Work Schedule"]
        ];
        return view('admin.work_schedules.assign', [
            'breadcrumbs' => $breadcrumbs,
            'locale' => $locale,
            'workSchedule' => $workSchedule,
            'employees' => $employees,
            'assignedEmployeeIDByWorkSchedule' => $assignedEmployeeIDByWorkSchedule,
        ]);
    }


    public function store($lang,Request $request,$id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        (new AssignWorkScheduleToEmployees())->execute($request,$id);

        Session::flash('success', 'Work Schedule is Assigned to Employees successfully');
        return redirect($locale . '/work-schedule')->with('locale', $locale);
    }
}
