<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\storeLanguage;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Session;

class LanguageController extends Controller
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
        $language = Language::all();

        return view('admin.languages.index')->with('languages', $language)->with('locale', $locale);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function create(storeLanguage $request)
    {
        $request->session()->forget('unauthorized_user');
        $language_exist = Language::where('name', $request->name)->first();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($language_exist == null) {
            Language::create([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Language is added successfully'));
        } else {
            Session::flash('error', trans('language.Language is already exist'));
        }
        if ($request->status == 1) {
            $status_old = Language::where('status', '=', 1)->where('name', '!=', $request->name)->get();
            foreach ($status_old as $old) {
                $language = language::find($old->id);
                $language->status = 0;
                $language->save();
            }
            $locale = $request->short_name;
            App::setLocale($locale);

            return redirect($locale.'/language')->with('locale', $locale);
        } else {
            return redirect($locale.'/language')->with('locale', $locale);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $language = Language::find($id);
        $language->name = $request->name;
        $language->short_name = $request->short_name;
        $language->status = $request->status;
        $language->save();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($request->status == 1) {
            $status_old = Language::where('status', '=', 1)->where('name', '!=', $request->name)->get();
            foreach ($status_old as $old) {
                $language = language::find($old->id);
                $language->status = 0;
                $language->save();
            }
            $locale = $request->short_name;
            App::setLocale($locale);
            Session::flash('success', trans('language.Language is updated successfully'));

            return redirect($locale.'/language')->with('locale', $locale);
        } else {
            $status_old = Language::where('status', '=', 1)->where('name', '!=', $request->name)->get();
            if (count($status_old) == 0) {
                $first_language = Language::first();
                $first_language->status = 1;
                $first_language->save();
                $locale = $first_language->short_name; // `en` or `es`
                App::setLocale($locale);
            }
            Session::flash('success', trans('language.Language is updated successfully'));

            return redirect($locale.'/language')->with('locale', $locale);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $language = Language::find($id);
        if ($language->status == 1) {
            $first_language = Language::first();
            $first_language->status = 1;
            $first_language->save();
            $language->delete();
            $locale = $first_language->short_name; // `en` or `es`
            App::setLocale($locale);
            Session::flash('success', trans('language.Language deleted successfully'));

            return redirect($locale.'/language')->with('locale', $locale);
        } else {
            $language->delete();
            $locale = $request->segment(1, ''); // `en` or `es`
            App::setLocale($locale);
            Session::flash('success', trans('language.Language deleted successfully'));

            return redirect($locale.'/language')->with('locale', $locale);
        }
    }
}
