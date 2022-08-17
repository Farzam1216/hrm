<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateDivision;
use App\Domain\Employee\Actions\DeleteDivision;
use App\Domain\Employee\Actions\GetAllDivisions;
use App\Domain\Employee\Actions\UpdateDivision;
use App\Domain\Employee\Actions\EditDivision;
use App\Domain\Hiring\Actions\StoreDivisionNotification;
use App\Domain\Hiring\Actions\StoreDivisionUpdateNotification;
use App\Domain\Hiring\Actions\StoreDivisionDeleteNotification;
use App\Http\Requests\storeDivision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

;

class DivisionController extends Controller
{
    /**
     * Show Teams Form The Storage.
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $divisions = (new GetAllDivisions())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Divisions"]
         ];
        return view('admin.division.index',[
            'breadcrumbs' => $breadcrumbs,
            'divisions'=> $divisions,
            'locale'=> $locale
        ]);
    }

    /**
     * Create New Teams.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(Request $request , $id = '')
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
       // $data=(new ViewAllDepartments())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/divisions",'name' => "Divisions"], 
            ['name' => "Add Division"]
         ];
            
            return view('admin.division.create',[
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale]);
    }
    public function store(storeDivision $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new CreateDivision())->execute($request);
        (new StoreDivisionNotification())->execute($request);

        return redirect($locale.'/divisions')->with('locale', $locale);
    }

    /**
     * Update division Details.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update( $lang,$id ,Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreDivisionUpdateNotification())->execute($request,$id);
        (new UpdateDivision())->execute($id, $request);

        return Redirect::to(url($locale.'/divisions'))->with('locale', $locale);
    }


    public function edit($locale, $division_id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['link' => "{$locale}/divisions",'name' => "division"], ['name' => "Edit Division"]
        ];
        $data = (new EditDivision())->execute($division_id);
        return view('admin.division.edit',
        ['breadcrumbs' => $breadcrumbs,
        'division'=>$data['division'],
        'locale'=> $locale]);
    }
    /**
     * Delete Team From The Storage.
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
        (new StoreDivisionDeleteNotification())->execute($id);
        (new DeleteDivision())->execute($id);

        return Redirect::to(url($locale.'/divisions'))->with('locale', $locale);
    }
}
