<?php


namespace App\Domain\Employee\Actions;


use App\Domain\Employee\Models\AssetsType;

class GetAssetTypeByID
{
    public function execute($id)
    {
        return AssetsType::where('id', $id)->first();
    }

}