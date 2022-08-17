<?php


namespace App\Domain\Approval\Actions;

class EditApproval
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute($id)
    {
        $data['approval']  = (new GetApproval())->execute($id);
        $data['defaultFields']  = (new GetDefaultFields())->execute($id);
        return $data;
    }
}
