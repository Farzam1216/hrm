<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Hiring\Models\Candidate;
use App\Models\Attendence\AttendanceSummary;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Mail;
use Session;

class DashboardController extends Controller
{

    /**
     * Show Dashboard With Mixed Details
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $dates = [];
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $candidates = Candidate::where('recruited', 0)->count();
        $male = Employee::where('status', '1')->where('gender', 'Male')->count();
        $female = Employee::where('status', '1')->where('gender', 'Female')->count();
        $months = [
            'January' => '1',
            'February' => '2',
            'March' => '3',
            'April' => '4',
            'May' => '5',
            'June' => '6',
            'July' => '7',
            'August' => '8',
            'September' => '9',
            'October' => '10',
            'November' => '11',
            'December' => '12',
        ];
        $attArr = [];
        $counts = [];
        foreach ($months as $month) {
            foreach (Employee::where('status', '1')->get() as $employee) {
                $weekend = Location::where('id', $employee->location_id)->first();
                $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, Carbon::now()->year);
                $days = 1;
//                if ($weekend != null) {
//                    for ($i = 1; $i <= $numberOfDays; $i++) {
//                        $now = Carbon::now();
//                        $date = Carbon::parse($i . "-" . $month . "-" . $now->year)->toDateString();
//                        if (in_array(Carbon::parse($date)->format('l'), json_decode($weekend->weekend)) == false) {
//                            $days += 1;
//                        }
//                    }
//                }
                $counts[$month][$employee->id] = (AttendanceSummary::where(
                    'employee_id',
                    $employee->id
                )->whereRaw('MONTH(date) = ?', $month)->whereRaw(
                    'YEAR(date) = ?',
                    date('Y')
                )->count() / $days) * 100;
            }
        }
        $averageAttendance = [];
        foreach ($counts as $date => $array) {
            $employeesCount = Employee::where('status', '1')->count();
            $averageAttendance[] = round((array_sum($array) / $employeesCount), 2);
        }

        $averageAttendance = json_encode($averageAttendance);
        $Chartmonths = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
        $chartMonths = json_encode($Chartmonths);

        $Designations = Designation::all();
        $chartEmployee = [];
        $DesignationName = [];
        foreach ($Designations as $Designation) {
            $chartEmp = Employee::where('designation_id', $Designation->designation_name)->where('status', '1')->count();
            if ($chartEmp > 0) {
                $chartEmployee[] = $chartEmp;
                $DesignationName[] = $Designation->designation_name;
            }
        }
        $replace = ['[', ']'];
        $DesignationName = str_replace($replace, '', json_encode($DesignationName));
        $designationSeries = implode(',', $chartEmployee);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return view('admin.dashboard.index')
            ->with('employee', Employee::where('status', '1')->orderBy('joining_date', 'Desc')->take(5)->get())
            ->with(
                'totalemployees',
                Employee::where('status', '1')->where('employment_status_id', 'permanent')->orwhere('employment_status_id', 'probation')->get()
            )
            ->with('designationSeries', $designationSeries)
            ->with('DesignationName', $DesignationName)
            ->with('averageAttendance', $averageAttendance)
            ->with('chartMonths', $chartMonths)
            ->with('male', $male)
            ->with('female', $female)
            ->with('applicants', $candidates)
            ->with('locale', $locale);
    }

    /**
     * Show Help Form
     * @param Request $request
     * @return Factory|View
     */
    public function help(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return view('Help.index')->with('locale', $locale);
    }

    /**
     * Send Help Email To HR
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contactUs(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = ['name' => "$request->name", 'messages' => "$request->message", 'email' => "$request->email"];
        try {
            Mail::send('Help.mail', $data, function ($message) use ($request) {
                $message->to('awaid.anjum@gmail.com')->subject($request->type);
                $message->from('noreply@glowlogix.com', "$request->email");
            });
            Session::flash('success', trans('language.Email Sent To the HR'));
        } catch (Exception $e) {
            Session::flash('error', trans('language.Email Not Send Please Set Email SMTP Configuration'));
        }
        return redirect()->back();
    }

    /**
     * Import Data View
     * @param Request $request
     * @return Factory|View
     */
    public function importdata(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return view('admin.dashboard.importData')->with('locale', $locale);
    }

    /**
     * Import Data To DB
     * @param Request $request
     * @return Factory|View
     */
    public function import(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        try {
            Artisan::call('db:seed', ['--class'=>'DocumentstableSeeder']);
            Artisan::call('db:seed', ['--class'=>'LeaveTypeSeeder']);
            Artisan::call('db:seed', ['--class'=>'BranchSeeder']);
            Artisan::call('db:seed', ['--class'=>'EmployeeLeaveTypeSeeder']);
            Artisan::call('db:seed', ['--class'=>'DesignationSeeder']);
            Artisan::call('db:seed', ['--class'=>'DepartmentSeeder']);
            Artisan::call('db:seed', ['--class'=>'CountrySeeder']);
            Session::flash('success', trans('language.Demo data imported successfully'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back()->with('locale', $locale);
    }
}
