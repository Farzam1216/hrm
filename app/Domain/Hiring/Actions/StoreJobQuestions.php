<?php

namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobOpening;

class StoreJobQuestions
{
    public function execute($request)
    {
        $data = JobOpening::create([
            'title' => $request->title,
            'location_id' => $request->location_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'description' => $request->description,
            'hiring_lead_id' => $request->hiring_lead_id,
            'status' => $request->job_status,
            'employment_status_id' => $request->employment_status_id,
            'minimum_experience' => $request->minimum_experience,
        ]);


        $jobs = $data->id;



        //Store Canned Question ID in Pivot Table
        (new InsertQuestionStatus())->execute($jobs, $request->optionalQuestions);

        //Store Custom Questions in Questions' Table and save ID in Pivot Table

        $customdata = (new StoreQuestions())->execute($request, $jobs);
    }
}
