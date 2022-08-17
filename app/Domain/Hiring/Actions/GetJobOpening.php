<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobOpening;



class GetJobOpening
{
    /**
     * @param 
     * get job openings
     */
    public function execute()
    {
        //Hide unauthorized user information menu.
        $jobs = JobOpening::with(['department'=>function ($query) {
            $query->withTrashed();
        }])->with(['designation'=>function ($query) {
            $query->withTrashed();
        }])->with(['locations'])->get();
        return $jobs;
    }
}
