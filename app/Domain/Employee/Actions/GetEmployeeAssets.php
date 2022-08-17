<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\AssetsType;

class GetEmployeeAssets
{
    //Employee Assets

    /**
     * @param $id
     *
     * @return array
     */
    public function execute($id)
    {
        $assets = Asset::where('employee_id', $id)->with('asset_type')->get();
        $asset_types = AssetsType::where('status', '1')->get();
        $data = [
            'assets' => $assets,
            'asset_types' => $asset_types,
        ];
        return $data;
    }
}
