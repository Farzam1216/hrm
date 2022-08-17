<?php

namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\JobOpening;

class UpdateJobQuestions
{
    public function execute($request, $id)
    {
        $job = JobOpening::find($id);
        $job->title = $request->title;
        $job->location_id = $request->location_id;
        $job->department_id = $request->department_id;
        $job->designation_id = $request->designation_id;
        $job->description = $request->description;
        $job->hiring_lead_id = $request->hiring_lead_id;
        $job->status = $request->job_status;
        $job->employment_status_id = $request->employment_status_id;
        $job->minimum_experience = $request->minimum_experience;


        //Delete unselected Canned Questions from Job Questions
        (new DeleteCannedQuestions())->execute($id, $request->optionalQuestions);

        //Update Canned Question ID in Pivot Table
        (new UpdateCannedQuestionId())->execute($id, $request->optionalQuestions);

        (new DeleteQuestions())->execute($request, $id);
        //Update Custom Questions in Questions and Job Questions table and store new questions in
        // Questions' Table and save ID in Pivot Table
        (new UpdateQuestions())->execute($request, $id);
        $job->save();
        return true;
    }
}
