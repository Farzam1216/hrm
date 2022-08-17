<?php

namespace App\Domain\Benefit\Actions;

class RetrieveParametersFromURL
{
    /**
     * @param $data
     * @return array
     */

    public function execute($data)
    {
        //0=>'emplpoyee ID', 1=>'GroupPlan ID', 2=>'Enrollment Status'S
        return explode("-", $data);
    }
}
