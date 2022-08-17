<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\Handbook\Actions\getHandbook;
use App\Domain\Handbook\Actions\getChapterById;
use App\Domain\Handbook\Actions\storeChapterData;
use App\Domain\Handbook\Actions\updateChapterData;
use App\Domain\Handbook\Actions\destroyChapterById;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user'); //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Company"], ['name' => "Handbook"]
        ];

        $data = (new getHandbook())->execute();

        return view('admin.handbook.index', ['chapters' => $data['chapters'], 'permissions' => $data['permissions'], 'breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user'); //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Company"], ['link' => $locale."/handbook/chapter", 'name' => "Handbook"], ['name' => "Add Chapter"]
        ];

        return view('admin.handbook.create-chapter', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->forget('unauthorized_user');

        $chapter = (new storeChapterData())->execute($request);

        if($chapter == 1)
        {
            Session::flash('success', trans('language.Chapter is created successfully'));

            return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
        }
        else
        {
            Session::flash('error', trans('language.Chapter already exist'));

            return redirect()->back()->withInput();
        }
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $chapter = (new updateChapterData())->execute($request);

        if($chapter == true)
        {
            Session::flash('success', trans('language.Chapter name is updated successfully'));

            return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
        }
        else
        {
            Session::flash('error', trans('language.Chapter name already exist'));

            return redirect()->back()->withInput();
        }    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $chapter = (new destroyChapterById())->execute($id);

        if($chapter == true)
        {
            Session::flash('success', trans('language.Chapter is deleted successfully'));
        }
        else
        {
            Session::flash('error', trans('language.Chapter not exist'));
        }

        return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
    }
}
