<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Actions\AuthorizeUser;
use App\Domain\TimeOff\Actions\AdjustBalances;
use App\Domain\TimeOff\Actions\GetAllPolicies;
use App\Domain\TimeOff\Actions\ViewEmployeeTimeOff;
use App\Http\Requests\requestTimeOffformValidation;
use App\Domain\Approval\Actions\StoreRequestTimeOff;
use App\Domain\Approval\Actions\UpdateRequestTimeOff;
use App\Domain\Approval\Actions\ApproveTimeOffRequest;
use App\Domain\TimeOff\Actions\EmployeeTimeOffHistory;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;
use App\Domain\Approval\Actions\DenyTimeOffRequestandSendNotification;
use App\Domain\TimeOff\Actions\CalculateUpcommingTimeOffByEmployeeAJAX;
use App\Domain\Approval\Actions\CancelTimeOffRequestandSendNotification;
use App\Domain\TimeOff\Actions\UpdateAccrualOptionAfterPolicyChangedAJAX;

class EmployeeTimeOffController extends Controller
{
    /**
     * @param $lang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($lang, $userID, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = Employee::find($userID);
        if (Auth::user()->id == $userID) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['name' => "Time Off Requests"]
            ];
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['name' => $employee->firstname . ' ' . $employee->lastname . ' Time Off Requests']
            ];
            (new ToggleEmployeeBasedMenuItems())->execute($userID);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);
        }

        $employeeTimeOff = new ViewEmployeeTimeOff();
        $data = $employeeTimeOff->execute($userID, $request);
        
        return view('admin.employee_time_off.index')
            ->with('time_off_types', $data['assignTimeOff'])
            ->with('locale', $locale)
            ->with('getHours', $data['getHours'])
            ->with('upcomingRequests', $data['upcomingRequests'])
            ->with('pendingRequests', $data['pendingRequests'])
            ->with('usedbalance', $data['usedbalance'])
            ->with('timeOffTransactions', json_encode($data['timeOffTransactions']))
            ->with('employee', $data['employee'])
            ->with('permissions', $data['permissions'])
            ->with('breadcrumbs', $breadcrumbs);
    }
    public function calculateTimeOff(Request $request)
    {
        $upcomingTimeOff = new CalculateUpcommingTimeOffByEmployeeAJAX();
        $data = $upcomingTimeOff->execute($request, Auth::user()->id);
        return response()->json($data);
    }
    //Store
    public function store($local, requestTimeOffformValidation $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $requestTimeoff = new StoreRequestTimeOff();
        $requestTimeoff->execute($request->all(), $id);
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
    public function updateTimeOff(Request $request, $locale, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $updateRequestTimeOff = new UpdateRequestTimeOff();
        $updateRequestTimeOff->execute($request->all(), $id);
        Session::flash('success', trans('language.You have successfully updated time off'));
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
    public function denyTimeOff(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        (new ToggleEmployeeBasedMenuItems())->execute($userID);
        App::setLocale($locale);
        $denyTimeOff = new DenyTimeOffRequestandSendNotification();
        $denyTimeOff->execute($request);
        Session::flash('success', trans('language.You have successfully denied time off'));
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
    public function approveRequestTimeOff(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $approvetimeOff = new ApproveTimeOffRequest();
        $approvetimeOff->execute($request);
        Session::flash('success', trans('language.You have successfully approved request'));
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
    public function adjustBalanceManually(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $adjustBalances = new adjustBalances();
        $adjustBalances->execute($request->all(), $userID);
        Session::flash('success', trans('language.You have successfully Adjusted Balance'));
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
    public function filterHistory(Request $request)
    {
        // FIXME:;
        $employeeID = $request->segment(3, Auth::id());
        $employeeTimeOffHistory = new EmployeeTimeOffHistory();
        $data = $employeeTimeOffHistory->execute($request, $employeeID);
        return $data;
    }
    public function changePolicy(Request $request)
    {
        $employeeID = $request->segment(3, Auth::id());
        $locale = $request->segment(1, ''); // `en` or `es`
        (new ToggleEmployeeBasedMenuItems())->execute($employeeID);
        App::setLocale($locale);
        $updateaccrual = new UpdateAccrualOptionAfterPolicyChangedAJAX();
        $data = $updateaccrual->execute($request, $employeeID);
        return response()->json($data);
    }

    public function policiestype(Request $request, $locale, $employee_id)
    {
        $policiesType = new GetAllPolicies();
        $data = $policiesType->execute($request, $employee_id);
        return response()->json($data);
    }
    public function saveAccrualOption(Request $request, $locale, $userId)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request['isSave'] = true;
        $save_accrual = new UpdateAccrualOptionAfterPolicyChangedAJAX();
        $data = $save_accrual->execute($request, $userId);
        Session::flash('success', trans('language.You Have Successfully Updated Accrual Option'));
        return redirect()->route('timeoff.index', [$locale, $request->employee_id]);
    }

    public function cancelTimeOff(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userID = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $cancelTimeOff = new CancelTimeOffRequestandSendNotification();
        $cancelTimeOff->execute($request->all(), $userID);
        Session::flash('success', trans('language.You have canceled time off request'));
        return redirect()->route('timeoff.index', [$locale, $userID]);
    }
}
