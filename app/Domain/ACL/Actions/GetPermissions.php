<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\EducationType;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeDocument;
use App\Domain\Employee\Models\EmployeeEmploymentStatus;
use App\Domain\Employee\Models\EmployeeJob;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\Employee\Models\Note;
use App\Domain\Employee\Models\VisaType;
use App\Domain\Task\Models\Task;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Domain\PerformanceReview\Models\PerformanceQuestionnaire;

class GetPermissions
{
    public function execute($id)
    {
        $permissions = [];
        $models = [
            Employee::class, Education::class, EducationType::class, VisaType::class, EmployeeVisa::class,
            EmployeeBenefit::class, BenefitGroup::class, EmployeeDependent::class, TimeOffType::class,
            EmployeeDocument::class, Policy::class, Note::class, Asset::class, Task::class, EmployeeJob::class, EmployeeEmploymentStatus::class, PerformanceQuestionnaire::class,
        ];
        foreach ($models as $key => $model) {
            $permissions = $model::getSelectedPermissions($id, $permissions);
        }

        return $permissions;
    }
}
