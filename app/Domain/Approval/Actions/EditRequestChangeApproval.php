<?php


namespace App\Domain\Approval\Actions;

class EditRequestChangeApproval
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute($employeeId, $approvalId)
    {
        $requestApprovals = (new GetRequesterApprovals())->execute('Standard', $employeeId);
        $data['requestApprovals'] = $requestApprovals->merge((new GetRequesterApprovals())->execute('Custom', $employeeId));
        $data['requestedFields'] = (new GetRequestedFields())->execute($data['requestApprovals'], $approvalId);
        $formFields = json_decode($data['requestedFields']->form_fields, true);
        $data['requestChangeApprovals'] = (new GetRequestedFieldsWithData())->execute($formFields, $employeeId);
        return $data;
    }
}
