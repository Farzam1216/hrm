<?php

namespace App\Domain\Poll\Actions;

use App\Domain\Poll\Models\Poll;
use App\Domain\Poll\Models\PollAnswer;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Poll\Actions\GetPollAnswerWithId;
use Auth;




class GetAllPolls
{
    /**
     * @param $id
     * @param $data
     */
    public function execute()
    {

        $polls =  Poll::all();
        $employeeId = Auth::id();

        for ($i = 0; $i < count($polls); $i++) {
            $pollAnswers = (new GetPollAnswerWithId())->execute($polls[$i]['id'],$employeeId);
            if (!empty($pollAnswers)) {
                $polls[$i]['attempted'] = true;
            } else {
                $polls[$i]['attempted'] = false;
            }
        }
        $data['poll'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['poll']['employee']);

        return $data = [
            'polls' => $polls,
            'permissions' => $data['permissions'],
        ];
    }
}
