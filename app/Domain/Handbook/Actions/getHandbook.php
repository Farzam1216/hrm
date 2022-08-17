<?php


namespace App\Domain\Handbook\Actions;

use Illuminate\Support\Facades\Log;
use App\Domain\Handbook\Models\Chapter;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;

class getHandbook
{
    public function execute()
    {
        $chapters = Chapter::with(['pages'])->get();
        $data['handbook'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['handbook']['employee']);

        return $data = [
            'chapters' => $chapters,
            'permissions' => $data['permissions'],
        ];
    }
}
