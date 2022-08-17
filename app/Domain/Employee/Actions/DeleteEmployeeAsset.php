<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Asset;

class DeleteEmployeeAsset
{
    /**
     * @param $id
     * @param $data
     *
     * @return array
     */
    public function execute($id)
    {
        Asset::find($id)->delete();
    }
}
