<?php

namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\Compensation;

class DestroyCompensationById
{
    public function execute($request)
    {
        $compensation = Compensation::find($request->compensation_id);

        if ($compensation) {
            $compensation->destroy($request->compensation_id);
        }
        
        return $compensation;
    }
}
