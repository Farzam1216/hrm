<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\DeleteEmployeeDocument;
use App\Domain\Employee\Actions\StoreEmployeeDocument;
use App\Domain\Employee\Actions\UpdateEmployeeDocument;
use App\Domain\Employee\Actions\ViewEmployeeDocuments;
use App\Domain\Employee\Actions\EditEmployeeDocuments;
use App\Http\Requests\storeEmployeeDoc;
use App\Http\Requests\updateEmployeeDoc;
use App\Domain\Employee\Actions\GetAllDocumentTypes;
use App\Domain\Employee\Actions\storeEmployeedocumentNotification;
use App\Domain\Employee\Actions\storeEmployeedocumentUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeedocumentDeleteNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Session;
class EmployeeDocumentController extends Controller
{
    /**
     * Show Personal Documents Of Employee Of Specific ID.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index($lang, $id, Request $request)
    {
        $data=(new ViewEmployeeDocuments())->execute($id, $this);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' =>"Employees"], ['name' => "Employee Documents"]
         ];
        return view('admin.employees.employee_documents.index',[
        'breadcrumbs' => $breadcrumbs,
        'docs'=> $data['docs'],
        'doc_types'=> $data['doc_types'],
        'employee_id'=> $id,
        'permissions'=> $data['permissions'],
        'locale'=> $locale]);
    }

    /**
     * Store Document Of Employee Of Specific ID.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $doc_types = (new GetAllDocumentTypes())->execute($request);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' =>"Employees"], ['link' => "$locale/employees/$id/docs",'name' => "Employee Documents"], ['name' => "Add Employee Document"]
         ];
        return view('admin.employees.employee_documents.create',['breadcrumbs' => $breadcrumbs,
        'locale'=> $locale,'doc_types' => $doc_types,'employee_id' => $id]);
    }
    /**
     * Store employee document.
     * Store a newly created Branch in storage.
     *
     * @param Request $request
     * @param storeEmployeeDoc $request
     *
     * @return RedirectResponse
     * @return Response
     */
    public function store($lang,$id,storeEmployeeDoc $request)
    { 
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmployeeDocument())->execute($request, $id);
        (new storeEmployeedocumentNotification())->execute($request);
        return Redirect::to(url($locale.'/employees/'.$id.'/docs'))->with('locale', $locale);
    }
    /** 
     * Display the specified resource. 
     * @param  int  $id 
     * @return \Illuminate\Http\Response 
     */  
    public function show($id)  
    {  
    
    //  
    }  
    /**
     * Edit Document Of Employee Of Specific ID.
     *
     * @param Request $request
     * @param $lang
     * @param $doc_id
     * @return RedirectResponse
     */
    public function edit($lang, $employee_id, $doc_id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' =>"Employees"], ['link' => "$locale/employees/$employee_id/docs",'name' => "Employee Documents"], ['name' => "Edit Employee Document"]
         ];
        $all_types = (new GetAllDocumentTypes())->execute();
        $data = (new EditEmployeeDocuments())->execute($doc_id);
        return view('admin.employees.employee_documents.edit',
            [ 
                'breadcrumbs' => $breadcrumbs,
                'doc_types' => $data['doc_types'],
                'employee_id' => $employee_id,
                'all_types' => $all_types,
            ]);
    }
    /**
     * Update Document Of Employee Of Specific ID.
     *
     * @param Request $request
     * @param $lang
     * @param $doc_id
     * @return RedirectResponse
     */
    public function update(updateEmployeeDoc $request, $lang, $id, $doc_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $documentedUpdated=(new UpdateEmployeeDocument())->execute($request, $doc_id);
        if ($documentedUpdated) {            
        (
            new storeEmployeedocumentUpdateNotification())->execute($request, $doc_id);
            Session::flash('success', trans('language.Document is updated successfully'));
        }
        return Redirect::to(url($locale.'/employees/'.$id.'/docs'))->with('locale', $locale);
    }

    /**
     * Delete Document Of Employee.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, $lang)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeedocumentDeleteNotification())->execute($request);
        $documentDeleted=(new DeleteEmployeeDocument())->execute($request);
        if ($documentDeleted) {
            Session::flash('success', trans('language.Document deleted successfully'));
        }
        return Redirect::to(url($locale.'/employees/'. $request->employee_id.'/docs'))->with('locale', $locale);
    }
}
