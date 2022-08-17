<?php


namespace App\Domain\Employee\Actions;


use App\Domain\Employee\Models\Asset;

class GetAssetByID
{
    public function execute($id)
    {
        return Asset::where('id', $id)->first();
    }
}