<?php


namespace App\Domain\Approval\Actions;

class ViewApprovals
{
    /**
     * @return mixed $data
     */
    public function execute()
    {
        $data['allEmployees'] = (new GetAllEmployees())->execute();
        $data['allCustomLevels'] = (new GetAllCustomAccessLevels())->execute();
        $data['approvalsAndPathTypes'] = (new GetApprovalsAndPathTypes())->execute();
        $data['statuses'] = (new GetEmploymentstatuses())->execute();
        return $data;
    }
}
