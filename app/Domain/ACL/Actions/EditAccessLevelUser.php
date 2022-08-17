<?php

namespace App\Domain\ACL\Actions;

class EditAccessLevelUser
{
    public function execute($id)
    {
        $data['selectedEmployees'] = (new SelectedEmployees())->execute($id);
        $data['availableEmployees'] = (new AvailableEmployees())->execute($id, $data['selectedEmployees']);
        $data['role'] = (new GetRole())->execute($id);
        return $data;
    }
}
