<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\CandidateStatus;

class AddStatus
{
    /**
     * @param $id
     * Add Candidate Status in CandidateStatus
     */
    public function execute($id)
    {
        CandidateStatus::create(
            [
                'candidate_id' => $id,
                'status' => 'New',
            ]
        );
    }
}
