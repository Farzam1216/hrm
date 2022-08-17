<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\AssetsType;
use Illuminate\Support\Facades\Log;
use Session;

class UpdateAssetType
{
    public function execute($id, $request)
    {
        $asset_type = AssetsType::find($id);
        $asset_type->name = $request->name;
        $asset_type->status = $request->status;
        try {
            $asset_type->save();
            Session::flash('success', trans('language.Assets Type is updated successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to update Asset Type.'));
        }
        return AssetsType::all();
    }
}
