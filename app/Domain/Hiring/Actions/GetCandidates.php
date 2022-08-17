<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Candidate;

class GetCandidates
{
    /**
     * @return Candidate[]|Builder[]|Collection
     */
    public function execute()
    {
        $recruit = 0;
        $candidates = Candidate::with('job', 'answers')
        ->where('recruited', $recruit)
            ->get();
        return $candidates;
    }
}
