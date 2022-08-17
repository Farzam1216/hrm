<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\AssetsType;

class UpdateEmployeeAsset
{
    /**
     * @param $data
     * @param $id
     *
     * @return array
     */
    public function execute($data, $id)
    {
        $employee_id = $data['employee_id'];
        $assets = Asset::find($id);
        $assets->asset_category = $data['asset_category'];
        $assets->asset_description = $data['asset_description'];
        $assets->serial = $data['serial'];
        $assets->assign_date = $data['assign_date'];
        $assets->return_date = $data['return_date'];
        $assets->employee_id = $employee_id;
        $assets->save();

        $assets->asset_type = AssetsType::find($assets->asset_category);
        return $assets;
    }
}
