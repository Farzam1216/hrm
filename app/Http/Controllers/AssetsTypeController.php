<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateAssetType;
use App\Domain\Employee\Actions\DeleteAssetType;
use App\Domain\Employee\Actions\GetAllAssetTypes;
use App\Domain\Employee\Actions\GetAssetByID;
use App\Domain\Employee\Actions\GetAssetTypeByID;
use App\Domain\Employee\Actions\UpdateAssetType;
use App\Domain\Employee\Models\AssetsType;
use App\Domain\Employee\Actions\StoreAssetTypeNotification;
use App\Domain\Employee\Actions\StoreAssetTypeUpdateNotification;
use App\Domain\Employee\Actions\StoreAssetTypeDeleteNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;


class AssetsTypeController extends Controller
{

    /**
     * List of Asset Types
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $asset_type = (new GetAllAssetTypes())->execute();
        $breadcrumbs = [
            ['link' => "$locale/asset-types", 'name' => "Asset-Type Management"], ['name' => "Assets"]
        ];
        return view('admin.asset_type.index', [
            'breadcrumbs' => $breadcrumbs,
            'asset_types' => $asset_type,
            'locale' => $locale,
        ]);
    }

    /**
     * Create Asset Types
     * @param Request $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/asset-types", 'name' => "Asset-Type Management"], ['name' => "Add Asset"]
        ];
        return view('admin.asset_type.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }
    public function store(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        //TODO:: Add Form Requests
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreAssetTypeNotification())->execute($request);
        $asset_type = (new CreateAssetType())->execute($request);
        Session::flash('success', trans('language.Asset is created successfully'));
        return redirect($locale.'/asset-types');

    }


    /**
     * Update Asset Types
     * @param $id
     * @param Request $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/asset-types", 'name' => "Asset-Type Management"], ['name' => "Edit Asset"]
        ];
        $asset_type = (new GetAssetTypeByID())->execute($id);
        return view('admin.asset_type.edit',
            ['asset_type' => $asset_type,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                ]);
    }
    public function update(Request $request, $locale, $id)
    {
        //TODO:: Add Form Requests
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        (new StoreAssetTypeUpdateNotification())->execute($request, $id);
        $asset_type = (new UpdateAssetType())->execute($id, $request);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('Asset-type is updated successfully'));
        return redirect($locale.'/asset-types');
    }

    /**
     * Delete Asset Type
     * @param $id
     * @param Request $request
     * @return Factory|View
     */
    public function delete(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreAssetTypeDeleteNotification())->execute($id);
        $asset_type = (new DeleteAssetType())->execute($id);

        return redirect()->back()->with('asset-types', $asset_type)->with('locale', $locale);
    }
}
