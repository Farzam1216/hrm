<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateDepartment;
use App\Domain\Employee\Actions\DeleteDepartment;
use App\Domain\Employee\Actions\GetAllDepartments;
use App\Domain\Employee\Actions\UpdateDepartment;
use App\Domain\Employee\Actions\EditDepartment;
use App\Domain\Employee\Models\Department;
use App\Http\Requests\storeDepartment;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Domain\Hiring\Actions\StoreDepartmentNotifications;
use App\Domain\Hiring\Actions\StoreDepartmentUpdateNotifications;
use App\Domain\Hiring\Actions\StoreDepartmentDeleteNotifications;

class DepartmentController extends Controller
{
    /**
     * Show All Departments.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $departments = (new GetAllDepartments())->execute();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Departments"]
        ];
        return view('admin.departments.index',[
            'breadcrumbs' => $breadcrumbs,
            'departments'=> $departments,
            'locale'=> $locale
            ]); 
    }

    /**
     * Store Department Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/departments",'name' => "Departments"], 
            ['name' => "Add Department"]
         ];
        return view('admin.departments.create',['breadcrumbs' => $breadcrumbs,'locale'=> $locale]);
    }
      /**
     * Store Department Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(storeDepartment $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDepartmentNotifications())->execute($request);
        (new CreateDepartment())->execute($request);

        return redirect($locale.'/departments')->with('locale', $locale);
    }
    /**
     * Edit Department Details.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function edit($locale, $department_id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['link' => "{$locale}/departments",'name' => "Departments"], ['name' => "Edit Department"]
        ];
        $data = (new EditDepartment())->execute($department_id);
        return view('admin.departments.edit',
        ['breadcrumbs' => $breadcrumbs,
        'department'=>$data['department'],
        'locale'=> $locale]);
    }
    /**
     * Update Department Detail Of Specific ID.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update(storeDepartment $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDepartmentUpdateNotifications())->execute($request,$id);
        (new UpdateDepartment())->execute($id, $request);

        return redirect($locale.'/departments')->with(['locale'=> $locale]);
    }

    /**
     * Delete Department Form The Storage.
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
        (new StoreDepartmentDeleteNotifications())->execute($id);
        (new DeleteDepartment())->execute($id);
        return redirect($locale.'/departments')->with(['locale'=> $locale]);
    }
}
