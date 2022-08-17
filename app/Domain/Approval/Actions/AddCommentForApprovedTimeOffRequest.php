<?php


namespace App\Domain\Approval\Actions;

use App\Domain\TimeOff\Models\RequestTimeoffComment;
use Illuminate\Support\Facades\Auth;

class AddCommentForApprovedTimeOffRequest
{
    /**
     * @param $data
     */
    public function execute($data)
    {
        //if comment is not given
        if ($data['comment'] == '') {
            RequestTimeoffComment::create([
                'request_time_off_id' => $data['requesttimeoffid'],
                'comment' => '',
                'commented_by' =>  Auth::id()
            ]);
        } else {
            RequestTimeoffComment::create([
                'request_time_off_id' => $data['requesttimeoffid'],
                'comment' => $data['comment'],
                'commented_by' =>  Auth::id()
            ]);
        }
    }
}
