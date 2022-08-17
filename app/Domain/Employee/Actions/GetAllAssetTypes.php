<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\AssetsType;

class GetAllAssetTypes
{
    public function execute()
    {
        return AssetsType::all();
    }
}
