<?php


namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\CompensationChangeReason;

class UpdateCompensationChangeReasonById
{
    public function execute($request, $id)
    {
        $changeReason = CompensationChangeReason::find($id);
        $changeReason->name = $request->name;
        $changeReason->change_occur = $request->change_occur;
        $changeReason->save();
        
        return $changeReason;
    }
}
