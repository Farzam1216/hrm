<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\AssetsType;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteAssetType
{
    public function execute($id)
    {
        try {
            $asset_type = AssetsType::find($id);
            $asset_type->delete();
            Session::flash('success', trans('language.Assets Type is deleted successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to delete Asset Type.'));
        }
        return AssetsType::all();
    }
}
