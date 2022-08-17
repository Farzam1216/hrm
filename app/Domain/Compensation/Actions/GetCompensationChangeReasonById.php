<?php

namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\CompensationChangeReason;

class GetCompensationChangeReasonById
{
    public function execute($id)
    {
        $changeReason = CompensationChangeReason::find($id);

        return $changeReason;
    }
}
