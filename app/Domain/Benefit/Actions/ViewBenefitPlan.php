<?php


namespace App\Domain\Benefit\Actions;

class ViewBenefitPlan
{
    public function execute()
    {
        return (new GetBenefitPlanTypes())->execute();
    }
}
