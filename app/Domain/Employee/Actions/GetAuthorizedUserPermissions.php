<?php


namespace App\Domain\Employee\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\EducationType;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Domain\Employee\Models\EmployeeEmploymentStatus;
use App\Domain\Employee\Models\EmployeeJob;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\SecondaryLanguage;
use App\Domain\Employee\Models\VisaType;
use App\Domain\Poll\Models\Poll;
use App\Domain\Task\Models\EmployeeTask;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Domain\Handbook\Models\Chapter;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\PayRoll\Models\PaySchedule;
use App\Domain\Compensation\Models\Compensation;
use App\Traits\AccessibleFields;

class GetAuthorizedUserPermissions
{
    use AccessibleFields;
    public function execute($employees)
    {
        $permissions = [];
        $permissions['employee'] = $this->getAccessibleFieldList(Employee::class, $employees);
        $permissions['job'] = $this->getAccessibleFieldList(EmployeeJob::class, $employees);
        $permissions['employmentStatus'] = $this->getAccessibleFieldList(EmployeeEmploymentStatus::class, $employees);
        $permissions['department'] = $this->getAccessibleFieldList(Department::class, $employees);
        $permissions['location'] = $this->getAccessibleFieldList(Location::class, $employees);
        $permissions['educationType'] = $this->getAccessibleFieldList(EducationType::class, $employees);
        $permissions['education'] = $this->getAccessibleFieldList(Education::class, $employees);
        $permissions['secondaryLanguage'] = $this->getAccessibleFieldList(SecondaryLanguage::class, $employees);
        $permissions['assets'] = $this->getAccessibleFieldList(Asset::class, $employees);
        $permissions['visaType'] = $this->getAccessibleFieldList(VisaType::class, $employees);
        $permissions['employeeVisa'] = $this->getAccessibleFieldList(EmployeeVisa::class, $employees);
        $permissions['employeeAccessLevel'] = $this->getAccessibleFieldList(Role::class, $employees);
        $permissions['employeeDocument'] = $this->getAccessibleFieldList(EmployeeDocument::class, $employees);
        $permissions['benefits'] = $this->getAccessibleFieldList(EmployeeBenefit::class, $employees);
        $permissions['benefitGroup'] = $this->getAccessibleFieldList(BenefitGroup::class, $employees);
        $permissions['dependents'] = $this->getAccessibleFieldList(EmployeeDependent::class, $employees);
        $permissions['benefitPlans'] = $this->getAccessibleFieldList(BenefitPlan::class, $employees);
        $permissions['timeofftype'] = $this->getAccessibleFieldList(TimeOffType::class, $employees);
        $permissions['policy'] = $this->getAccessibleFieldList(Policy::class, $employees);
        $permissions['tasks'] = $this->getAccessibleFieldList(EmployeeTask::class, $employees);
        $permissions['handbook'] = $this->getAccessibleFieldList(Chapter::class, $employees);
        $permissions['poll'] = $this->getAccessibleFieldList(Poll::class, $employees);
        $permissions['performance'] = $this->getAccessibleFieldList(PerformanceQuestionnaire::class, $employees);
        $permissions['attendance'] = $this->getAccessibleFieldList(EmployeeAttendance::class, $employees);
        $permissions['holidays'] = $this->getAccessibleFieldList(Holiday::class, $employees);
        $permissions['paySchedule'] = $this->getAccessibleFieldList(PaySchedule::class, $employees);
        $permissions['compensation'] = $this->getAccessibleFieldList(Compensation::class, $employees);
        
        return $permissions;
    }
}
