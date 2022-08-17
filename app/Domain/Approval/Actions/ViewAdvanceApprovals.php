<?php


namespace App\Domain\Approval\Actions;

class ViewAdvanceApprovals
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute()
    {
        $data['allEmployees'] = (new GetAllEmployees())->execute();
        $data['allCustomLevels'] = (new GetAllCustomAccessLevels())->execute();
        $data['approvalsAndPathTypes'] = (new GetApprovalsAndPathTypes())->execute();
        return $data;
    }
}
