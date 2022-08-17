<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateAssetType;
use App\Domain\Employee\Actions\CreateEmployeeAsset;
use App\Domain\Employee\Actions\DeleteEmployeeAsset;
use App\Domain\Employee\Actions\GetAssetByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;
use App\Domain\Employee\Actions\UpdateEmployeeAsset;
use App\Domain\Employee\Actions\ViewEmployeeAssets;
use App\Domain\Employee\Actions\storeEmployeeAssetNotification;
use App\Domain\Employee\Actions\storeEmployeeAssetUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeeAssetDeleteNotification;
use App\Http\Requests\validateAsset;
use Illuminate\Http\Request;
use App\Domain\Employee\Actions\GetAllAssetTypes;
use App\Domain\Employee\Models\AssetsType;
use Session;
use Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$lang, $id)
    {
        $data=(new ViewEmployeeAssets())->execute($id, $this);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Asset Management"], ['name' => "Assets"]
        ];
        return view('admin.assets.index', [
            'assets' => $data['info']['assets'],
            'asset_types' => $data['info']['asset_types'],
            'employee_id' => $id,
            'permissions' => $data['permissions'],
            'breadcrumbs' => $breadcrumbs,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $lang ,$id)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/employees/$id/assets", 'name' => "Asset Management"], ['name' => "Add Asset"]
        ];
        $asset_type = (new GetAllAssetTypes())->execute($request);
        return view('admin.assets.create', ['breadcrumbs' => $breadcrumbs,
            'employee_id' => $id,
            'assets_type' => $asset_type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(validateAsset $request)
    {
//        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee_asset = (new CreateEmployeeAsset())->execute($request->all());
        if($employee_asset){
            (new storeEmployeeAssetNotification())->execute($request);
            Session::flash('success', trans('language.Asset is Assigned successfully'));
        }
        return redirect($locale.'/employees/'.$request->employee_id.'/assets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, $employee_id, $asset_id, Request $request)
    {
        (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/employees/$employee_id/assets", 'name' => "Asset Management"], ['name' => "Edit Asset"]
        ];
        $employee_asset = (new GetAssetByID())->execute($asset_id);
        $asset_types = (new GetAllAssetTypes())->execute();
        return view('admin.assets.edit',
            [ 'employee_asset' => $employee_asset,
                'breadcrumbs' => $breadcrumbs,
                'employee_id' => $employee_id,
                'asset_id' => $asset_id,
                'asset_types' => $asset_types,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang, $employee_id, $asset_id)
    {
        (new UpdateEmployeeAsset())->execute($request->all(), $asset_id);
        (new storeEmployeeAssetUpdateNotification())->execute($request,$asset_id);
        Session::flash('success', trans('Asset is updated successfully'));
        return redirect($lang.'/employees/'.$employee_id.'/assets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$lang, $employee_id, $asset_id)
    {
        (new storeEmployeeAssetDeleteNotification())->execute($request,$asset_id);
        (new DeleteEmployeeAsset())->execute($asset_id);
        Session::flash('success', trans('Asset is deleted successfully'));
        return redirect($lang.'/employees/'.$employee_id.'/assets');

    }
}
