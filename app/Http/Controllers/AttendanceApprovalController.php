<?php

namespace App\Http\Controllers;

use App\Domain\Attendance\Actions\GetAttendanceApproval;
use App\Domain\Attendance\Actions\StoreAttendanceApproval;
use App\Domain\Attendance\Models\AttendanceApproval;
use App\Domain\Attendance\Models\AttendanceApprovelComments;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Employee\Actions\ViewAllEmployees;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App;

class AttendanceApprovalController extends Controller
{
    public function index($lang, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        //FIXME: $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        App::setLocale($locale);
        $breadcrumbs = [
            [ 'name' => "Setting"], ['name' => "Attendance Approvals"]
        ];

        $approvals = (new GetAttendanceApproval())->execute();

        return view('admin.attendance-approval.index',
            [
                'breadcrumbs' => $breadcrumbs,
                'approvals' => $approvals,
                'locale' => $locale,
            ]);
    }

    public function store($lang, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreAttendanceApproval())->execute($request);
        Session::flash('success', trans('Attendance approval decision saved Successfully'));

        return redirect($locale . '/employee-attendance-approval')->with('locale', $locale);
    }

}
