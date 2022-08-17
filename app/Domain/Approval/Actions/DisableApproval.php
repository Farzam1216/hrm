<?php


namespace App\Domain\Approval\Actions;

use App\Domain\Approval\Models\Approval;

class DisableApproval
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        $approvalId = $data->route('id');
        Approval::find($approvalId)->update([
            'status' => 0,
        ]);
    }
}
