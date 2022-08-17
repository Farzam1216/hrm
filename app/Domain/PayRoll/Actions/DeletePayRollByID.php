<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;

class DeletePayRollByID
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($id)
    {
        PayRoll::find($id)->delete();
        return true;
    }
}
