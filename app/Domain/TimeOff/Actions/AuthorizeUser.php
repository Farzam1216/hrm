<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Traits\AccessibleFields;

class AuthorizeUser
{
    use AccessibleFields;
    /**
     * @param $policyMethod
     * @param $controllerName
     * @param $modelName
     * @param $employees
     * @return mixed
     */
    public function execute($policyMethod, $controllerName, $modelName, $employees)
    {
        $models = [
            'timeOffType' => TimeOffType::class,
            'policy' => Policy::class,
        ];
        return $controllerName->authorize($policyMethod, [$models[$modelName], $employees]);
    }
}
