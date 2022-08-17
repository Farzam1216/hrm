<?php


namespace App\Domain\Approval\Actions;

class UpdateApprovalWorkflow
{
    /**
     * @param $data
     * @return
     */
    public function execute($data)
    {
        if ($data['approvalId'] > 2 && !isset($data['advanceApprovalOptionId'])) {
            (new UpdateApprovalRequester())->execute($data);
        }
        //update approval hierarchy w.r.t approval ID
        $levelNumber = 0;
        $flag=true;
        foreach ($data['dropdownLevel'] as $key => $value) {
            ++$levelNumber;
            if ($value == 'AccessLevels') {
                // $hierarchyLevelNumber = $key;
                (new StoreApprovalHierarchy())->execute([$value => $data['customLevels' . $key]], $levelNumber, $data, $flag);
                continue;
            }
            if ($value == 'SpecificPerson') {
                $specificPersonID = reset($data['specific_employee']);
                (new StoreApprovalHierarchy())->execute([$value => $specificPersonID], $levelNumber, $data, $flag);
                array_shift($data['specific_employee']);
                continue;
            } else {
                (new StoreApprovalHierarchy())->execute([$value => 'none'], $levelNumber, $data, $flag);
                continue;
            }
        }
    }
}
