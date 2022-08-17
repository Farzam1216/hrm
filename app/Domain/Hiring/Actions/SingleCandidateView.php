<?php


namespace App\Domain\Hiring\Actions;

class  SingleCandidateView
{
    public function execute($lang, $id)
    {
        $data = (new GetSingleCandidate())->execute($id);
        $job_id = $data['candidate']->job_id;
        $data['email'] = (new GetEmail())->execute($id, $job_id);
        if (!$data['email']) {
            $data['email'] = null;
        }
        $data['allTemplates'] = (new AllTemplates())->execute();
        return $data;
    }
}
