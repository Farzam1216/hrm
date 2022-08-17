<?php

namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\CompensationChangeReason;

class DestroyCompensationChangeReasonById
{
    public function execute($id)
    {
        $changeReason = CompensationChangeReason::find($id);

        if ($changeReason) {
            $changeReason->destroy($id);
        }
        
        return $changeReason;
    }
}
