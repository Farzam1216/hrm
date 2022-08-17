<?php


namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\CompensationChangeReason;

class StoreCompensationChangeReason
{
    public function execute($request)
    {
        $changeReason = new CompensationChangeReason();
        $changeReason->name = $request->name;
        $changeReason->save();
        
        return $changeReason;
    }
}
