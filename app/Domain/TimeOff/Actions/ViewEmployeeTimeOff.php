<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Http\Controllers\EmployeeTimeOffController;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Auth;

class ViewEmployeeTimeOff
{
    /**
     * @param $userID
     * @param $request
     * @return array
     */
    public function execute($userID, $request)
    {
        if ($userID != Auth::id()) {
            $employeeService = new EmployeeService();
            session(['unauthorized_user' => $employeeService->getEmployee($userID)]);
        } else {
            $request->session()->forget('unauthorized_user');
        }
        //getting assigned types, transactions for matching assigntimeoff id and employee id
        $timeOffTypeByEmployee = new GetTimeOffTypeByEmployee();
        $assignTimeOff = $timeOffTypeByEmployee->execute($userID);
        //calculating transactions
        $timeOffTransactionsByEmployee = new GetTimeOffTransactionsByEmployee();
        $timeOffTransactions = $timeOffTransactionsByEmployee->execute($userID);
        //show upcoming requests
        $upcomingRequestsTimeOff = new UpcomingRequestTimeOff();
        $upcomingRequests = $upcomingRequestsTimeOff->execute($userID);
        //show outstanding pending requests
        $pendingRequestsTimeOff = new PendingRequestTimeOff();
        $pendingRequests = $pendingRequestsTimeOff->execute($userID);
        //get hours, request time off detail for checks
        $timeOffDetails = new GetRequestedTimeOffDetailsByEmployee();
        $getHours = $timeOffDetails->execute($userID);
        //get used hours balance in carousal
        $usedTransactions =new GetUsedTransactionsByEmployee();
        $usedbalance = $usedTransactions->execute($userID);
        //Get employee details
        $getEmployee = new GetEmployeeByID();
        $employee = $getEmployee->execute($userID);
        $permissionsByEmployee = new GetTimeOffPermissionsByEmployee();
        $permissions = $permissionsByEmployee->execute([$employee]);
        $EmployeeTimeOffController = new EmployeeTimeOffController();
        $authorizeUser = new AuthorizeUser();
        $authorizeUser->execute("view", $EmployeeTimeOffController, "timeOffType", [$employee]);
        $data = [
            'assignTimeOff' => $assignTimeOff,
            'getHours' => $getHours,
            'upcomingRequests' => $upcomingRequests,
            'pendingRequests' => $pendingRequests,
            'usedbalance' => $usedbalance,
            'timeOffTransactions' => $timeOffTransactions,
            'employee' => $employee,
            'permissions' => $permissions,
        ];
        return $data;
    }
}
