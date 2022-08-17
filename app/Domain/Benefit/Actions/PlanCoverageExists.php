<?php

namespace App\Domain\Benefit\Actions;

class PlanCoverageExists
{
    /**
     * @param $planCoverage
     * @return bool
     */
    public function execute($planCoverage)
    {
        if ($planCoverage) {
            return true;
        } else {
            return false;
        }
    }
}
