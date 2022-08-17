<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateVisaType;
use App\Domain\Employee\Actions\DeleteVisaType;
use App\Domain\Employee\Actions\GetAllVisaTypes;
use App\Domain\Employee\Actions\GetAssetTypeByID;
use App\Domain\Employee\Actions\GetVisaTypeByID;
use App\Domain\Employee\Actions\UpdateVisaType;
use App\Domain\Employee\Actions\StoreVisaTypeNotifications;
use App\Domain\Employee\Actions\StoreVisaTypeUpdateNotifications;
use App\Domain\Employee\Actions\StoreVisaTypeDeleteNotifications;
use App\Http\Requests\validateVisaType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class VisaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $visaType = (new GetAllVisaTypes())->execute();
        $breadcrumbs = [
            ['link' => "$locale/visa-types", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Types"]
        ];
//        $breadcrumbs = [
//            ['link' => "$locale/visatype", 'name' => "Settings | Types"], ['name' => ""]
//        ];
        return view('admin.visa_type.index')->with('visatypes', $visaType)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/visa-types", 'name' => "Settings | Types | Visa Type |"], ['name' => "Add Visa Type"]
        ];
        return view('admin.visa_type.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(validateVisaType $request)
    {
        (new StoreVisaTypeNotifications())->execute($request);
        (new CreateVisaType())->execute($request);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/visa-types')->with('locale', $locale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/visa-types", 'name' => "Settings | Types | Visa Type |"], ['name' => "Edit Visa Type"]
        ];
        $visa_type = (new GetVisaTypeByID())->execute($id);
        return view('admin.visa_type.edit',
            ['visa_type' => $visa_type,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
            ]);
    }

    /**
     * @param $lang
     * @param validateVisaType $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($lang, validateVisaType $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreVisaTypeUpdateNotifications())->execute($request,$id);
        (new UpdateVisaType())->execute($id, $request);

        return redirect($locale.'/visa-types')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function destroy($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        (new StoreVisaTypeDeleteNotifications())->execute($id);
        (new DeleteVisaType())->execute($id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/visa-types')->with('locale', $locale);
    }
}
