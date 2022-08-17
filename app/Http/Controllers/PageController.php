<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\Handbook\Models\Page;
use App\Domain\Handbook\Models\Chapter;
use App\Domain\Handbook\Actions\getPageById;
use App\Domain\Handbook\Actions\storePageData;
use App\Domain\Handbook\Actions\updatePageData;
use App\Domain\Handbook\Actions\getChapterById;
use App\Domain\Handbook\Actions\destroyPageById;
use App\Domain\Handbook\Actions\destroyChapterById;
use App\Domain\Handbook\Actions\getChapterByIdWithPages;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $locale, $chapter_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $chapter = (new getChapterById())->execute($chapter_id);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Company"], ['link' => $locale."/handbook/chapter", 'name' => "Handbook"], ['link' => $locale."/handbook/chapter", 'name' => $chapter->name], ['name' => "Add Page"]
        ];

        return view('admin.handbook.create-page', ['chapter' => $chapter, 'breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
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

        $page = (new storePageData())->execute($request);

        if($page == true)
        {
            Session::flash('success', trans('language.Page is created successfully'));

            return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
        }
        else
        {
            Session::flash('error', trans('language.Page title already exist'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $chapter_id, $page_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $selected_page = (new getPageById())->execute($page_id);
        $chapter = (new getChapterByIdWithPages())->execute($chapter_id);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Company"], ['link' => $locale."/handbook/chapter", 'name' => "Handbook"], ['link' => $locale."/handbook/chapter", 'name' => $chapter[0]['name']], ['name' => "Show Page"]
        ];

        return view('admin.handbook.show', ['selected_page' => $selected_page, 'chapter' => $chapter, 'breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $chapter_id, $page_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $page = (new getPageById())->execute($page_id);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Company"], ['link' => $locale."/handbook/chapter", 'name' => "Handbook"], ['link' => $locale."/handbook/chapter", 'name' => Chapter::find($chapter_id)->name], ['name' => "Edit Page"]
        ];

        return view('admin.handbook.edit', ['page' => $page, 'breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
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

        $page = (new updatePageData())->execute($request);

        if($page == true)
        {
            Session::flash('success', trans('language.Page is updated successfully'));

            return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
        }
        else
        {
            Session::flash('error', trans('language.Page title already exist'));

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $chapter_id, $page_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $page = (new destroyPageById())->execute($page_id);

        if($page == true)
        {
            Session::flash('success', trans('language.Page is deleted successfully'));
        }
        else
        {
            Session::flash('error', trans('language.Page not exist'));
        }

        return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Handbook  $handbook
     * @return \Illuminate\Http\Response
     */
    public function destroyPageWithChapter(Request $request, $locale, $chapter_id, $page_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $page = (new destroyPageById())->execute($page_id);
        $checkPage = Page::where('chapter_id', $chapter_id)->first();

        if($page == true && $checkPage == '')
        {
            $chapter = (new destroyChapterById())->execute($chapter_id);
            if($chapter == true)
            {
                Session::flash('success', trans('language.Page with chapter is deleted successfully'));
            }
            else
            {
                Session::flash('success', trans('language.Chapter not exist'));
            }
        }
        else
        {
            Session::flash('error', trans('language.Page with chapter not exist'));
        }

        return redirect()->route('chapter.index', [$locale])->with('locale', $locale);
    }
}
