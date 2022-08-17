<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EducationType;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\SecondaryLanguage;
use App\Domain\Employee\Models\VisaType;
use App\Domain\Compensation\Models\CompensationChangeReason;
use App\Domain\PayRoll\Models\PaySchedule;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class GetInformationRequiredToEditEmployee
{
    //OldName: EditEmployeeInformation

    /**
     * @param $id
     *
     * @return array
     */
    public function execute($id)
    {
        $employee = Employee::with([
            'bankAccount',
            'employmentStatus',
            'department',
            'Location',
            'designation',
            'employeeCompensations' => function ($query) {
                $query->orderBy('status', 'asc');
            }, 
            'employeeCompensations.changeReason',
            'employeeCompensations.paySchedule',
            'assignedPaySchedule'
        ])->find($id);

        if (!$employee) {
            abort(404);
        }
        $currentUser = Auth::id();
        if (Auth::id() == $id) {
            $disableFields = (new DisableFieldWhenApprovalRequested())->execute($id);
        } else {
            $disableFields = [];
        }
        $data = [
            'employee' => $employee,
            'disableFields' => $disableFields,
        ];
        $data['visaType'] = VisaType::all();
        $data['country'] = Country::all();
        $data['educationType'] = EducationType::all();
        $data['secondaryLanguage'] = SecondaryLanguage::all();
        $data['changeReasons'] = CompensationChangeReason::all();
        $data['paySchedules'] = PaySchedule::all();
        return $data;
    }
}
