<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\SecondaryLanguage;
use App\Domain\Employee\Actions\EditSecondaryLanguage;
use App\Http\Requests\validateSecondaryLanguage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Session;

class SecondaryLanguageController extends Controller
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
        $secondaryLanguage = SecondaryLanguage::all();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['name' => "Secondary Language"]
        ];
        return view('admin.secondary_language.index')->with(['breadcrumbs' => $breadcrumbs,'secondaryLanguage'=> $secondaryLanguage,'locale'=> $locale]);
    }

     /**
     * Store Secondary Language Details.
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
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/secondarylanguage",'name' => "Secondary Language"], 
            ['name' => "Add Language"]
         ];
        return view('admin.secondary_language.create',['breadcrumbs' => $breadcrumbs,'locale'=> $locale]);
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
    public function store(validateSecondaryLanguage $request)
    {
        $secondarylanguage_exist = SecondaryLanguage::where('language_name', $request->language_name)->first();
        if ($secondarylanguage_exist == null) {
            $secondarylanguagee = SecondaryLanguage::create([
                'language_name' => $request->language_name,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Secondary Language is created successfully'));
        } else {
            Session::flash('error', trans('language.Secondary Language with this name already exist'));
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/secondarylanguage')->with('locale', $locale);
    }
/**
     * Edit Secondary Language Details.
     *
     * @param Request $request
     * @param $lang
     * @param $id
     *
     * @return RedirectResponse
     */
    public function edit($locale, $secondary_language_id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],
            ['link' => "{$locale}/secondarylanguage",'name' => "Secondary Language"], ['name' => "Edit Language"]
        ];
        $data = (new EditSecondaryLanguage())->execute($secondary_language_id);
        return view('admin.secondary_language.edit',
        ['breadcrumbs' => $breadcrumbs,
        'secondarylanguage'=>$data['secondarylanguage'],
        'locale'=> $locale]);
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
    public function update($lang, validateSecondaryLanguage $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $secondarylanguage = SecondaryLanguage::find($id);
        $secondarylanguage->language_name = $request->language_name;
        $secondarylanguage->status = $request->status;
        $secondarylanguage->save();
        Session::flash('success', trans('language.Secondary Language is updated successfully'));

        return redirect($locale.'/secondarylanguage')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $secondarylanguage = SecondaryLanguage::find($id);
        $secondarylanguage->delete();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Secondary Language deleted successfully'));

        return redirect($locale.'/secondarylanguage')->with('locale', $locale);
    }
}
