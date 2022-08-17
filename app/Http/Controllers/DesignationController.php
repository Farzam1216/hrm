<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateDesignation;
use App\Domain\Employee\Actions\DeleteAllDesignations;
use App\Domain\Employee\Actions\DeleteDesignation;
use App\Domain\Employee\Actions\GetAllDesignationsWithEmployee;
use App\Domain\Employee\Actions\UpdateDesignation;
use App\Domain\Employee\Actions\EditDesignation;
use App\Domain\Employee\Models\Designation;
use App\Domain\Hiring\Actions\StoreDesignationNotifications;
use App\Domain\Hiring\Actions\StoreDesignationUpdateNotifications;
use App\Domain\Hiring\Actions\StoreDesignationDeleteNotifications;
use App\Http\Requests\storeDesignation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DesignationController extends Controller
{
    /**
     * Show All Designations.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $designations = (new GetAllDesignationsWithEmployee())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Designations"]
        ];
        return view('admin.designations.index'
        ,[
            'breadcrumbs' => $breadcrumbs,
            'designations'=> $designations,
            'locale'=> $locale
        ]);
    }

    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/designations",'name' => "Designations"], ['name' => "Add New Designation"]
         ];
        return view('admin.designations.create',['breadcrumbs' => $breadcrumbs,'locale'=> $locale]);
    }

    /**
     * Store a newly created Branch in storage.
     *
     * @param storeDesignation $request
     *
     * @return Response
     */
    public function store(storeDesignation $request)
    { 
         $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new CreateDesignation())->execute($request);
        (new StoreDesignationNotifications())->execute($request);
        return redirect($locale.'/designations')->with('locale', $locale);
    }

     /**
     * Show the form for editing the Branch.
     *
     * @param Branch
     * @param Request $request
     *
     * @return Response
     */
    public function edit($locale, $designation_id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['link' => "{$locale}/designations", 'name' => "Designations"], ['name' => "Edit Designation"]
         ];
        $data = (new EditDesignation())->execute($designation_id);
        return view('admin.designations.edit',
        ['breadcrumbs' => $breadcrumbs,
        'designations'=>$data['designation'],
        'locale'=> $locale]);
    }
    /**
     * Update Designation Of Specific ID.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update($locale, storeDesignation $request, $id)
    {   
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDesignationUpdateNotifications())->execute($request, $id);
        (new UpdateDesignation())->execute($id, $request);
        return redirect($locale.'/designations')->with('locale', $locale);
    }

    /**
     * Delete Designation OF Specific ID.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDesignationDeleteNotifications())->execute($id);
        (new DeleteDesignation())->execute($id);
        return redirect($locale.'/designations')->with('locale', $locale);
    }

    /**
     * Delete All Designation.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAll(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteAllDesignations())->execute();

        return redirect($locale.'/designations')->with('locale', $locale);
    }
}
