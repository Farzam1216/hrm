<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Candidate;
use App\Domain\Hiring\Models\JobOpening;
use File;

class DeleteJob
{
    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function execute($id)
    {
        $job = JobOpening::with('que')->where('id', $id)->first();
        $candidates = Candidate::with('answers')->where('job_id', $id)->get();
        /*-----Delete Candidate with Answers-------*/
        foreach ($candidates as $candidate) {
            if ($candidate->answers) {
                foreach ($candidate->answers as $answer) {
                    $filepath = $answer->answer;
                    if (File::exists($filepath)) {
                        File::delete($filepath);
                        $answer->delete();
                    } else {
                        $answer->delete();
                    }
                }
            }
            $candidate->delete();
        }

        if ($job->que) {
            foreach ($job->que as $ques) {
                $ques->jobquestions->delete();
                if ($ques->type_id != 1) {
                    $ques->delete();
                }
            }
        }
        $job->delete();
        return true;
    }
}
