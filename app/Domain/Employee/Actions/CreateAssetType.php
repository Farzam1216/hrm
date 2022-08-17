<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\AssetsType;
use Illuminate\Support\Facades\Log;
use Session;

class CreateAssetType
{
    public function execute($request)
    {
        try {
            AssetsType::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Assets Type is added successfully'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', trans('language.Failed to add new asset type.'));
        }
        return AssetsType::all();
    }
}
