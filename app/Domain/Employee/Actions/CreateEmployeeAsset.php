<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Asset;
use App\Domain\Employee\Models\AssetsType;

class CreateEmployeeAsset
{
    /**
     * @param $data
     *
     * @return array
     */
    public function execute($data)
    {
        $employeeId = $data['employee_id'];
        $assets = new Asset();
        $assets->asset_category = $data['asset_category'];
        $assets->asset_description = $data['asset_description'];
        $assets->serial = $data['serial'];
        $assets->assign_date = $data['assign_date'];
        $assets->return_date = $data['return_date'];
        $assets->employee_id = $employeeId;
        $assets->save();

        $assets->asset_type = AssetsType::find($assets->asset_category);
        return $assets;
    }
}
