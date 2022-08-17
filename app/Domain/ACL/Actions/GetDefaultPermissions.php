<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Benefit\Models\BenefitGroup as BenefitGroup;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent as EmployeeDependent;
use App\Domain\Employee\Models\Asset as Asset;
use App\Domain\Employee\Models\Education as Education;
use App\Domain\Employee\Models\EducationType as EducationType;
use App\Domain\Employee\Models\Employee as Employee;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Domain\Employee\Models\EmployeeEmploymentStatus;
use App\Domain\Employee\Models\EmployeeJob;
use App\Domain\Employee\Models\EmployeeVisa as EmployeeVisa;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Employee\Models\Note as Note;
use App\Domain\Employee\Models\VisaType as VisaType;
use App\Domain\Task\Models\Task as Tasks;
use App\Domain\TimeOff\Models\Policy as Policy;
use App\Domain\TimeOff\Models\TimeOffType as TimeOffType;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire as PerformanceQuestionnaire;

class GetDefaultPermissions
{
    public function execute($roleType)
    {
        //Array to append all default permissions in single array without extra indexing
        $permissions = [];
        $models = [
            Employee::class, Education::class, EducationType::class, VisaType::class, EmployeeVisa::class, EmployeeBenefit::class, BenefitGroup::class, EmployeeDependent::class,
            TimeOffType::class, EmployeeDocument::class, Policy::class, Note::class, Asset::class, Tasks::class, EmployeeEmploymentStatus::class, EmployeeJob::class, PerformanceQuestionnaire::class
        ];
        foreach ($models as $key => $model) {
            $permissions = (new GetModelPermissions())->execute($model, $roleType, $permissions);
        }
        return $permissions;
    }
}
