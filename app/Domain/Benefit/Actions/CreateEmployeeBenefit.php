<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;

class CreateEmployeeBenefit
{
    public function execute($data, $controller)
    {
        $attributes = (new RetrieveParametersFromURL())->execute($data);
        $benefitData = (new GetBenefitGroupPlanDetails())->execute($attributes);
        $employee=(new GetEmployeeByID())->execute($attributes[0]);
        $dataArray['employeeBenefitStatus'] = (new GetEmployeeBenefitStatusDetails())->execute($attributes);
        (new AuthorizeUser())->execute("create", $controller, "employeeBenefits", [$employee], $benefitData['benefitPlanDetails']->id);
        (new ToggleEmployeeBasedMenuItems())->execute($attributes[0]);
        $dataArray['benefitData'] = $benefitData;
        $dataArray['attributes'] = $attributes;
        $dataArray['currencies'] = (new GetAllCurrenciesList())->execute();
        return $dataArray;
    }
}
