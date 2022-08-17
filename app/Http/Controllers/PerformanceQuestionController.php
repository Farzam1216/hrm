<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\PerformanceReview\Actions\GetQuestions;
use App\Domain\PerformanceReview\Actions\StoreQuestion;
use App\Domain\PerformanceReview\Actions\GetQuestionById;
use App\Domain\PerformanceReview\Actions\UpdateQuestionById;
use App\Domain\PerformanceReview\Actions\DestroyQuestionById;

class PerformanceQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['name' => "Questions"]
        ];

        $questions = (new GetQuestions)->execute();

        return view('admin.performance_review.questions.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questions', $questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('questions.index', [$locale]), 'name' => "Questions"], ['name' => "Add"]
        ];

        return view('admin.performance_review.questions.create')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreQuestion)->execute($request);

        if ($data['options'] == 'null') {
            Session::flash('error', trans('language.Option field cannot be empty'));
            return redirect()->back()->withInput();
        }
        if ($data['options'] == false) {
            Session::flash('error', trans('language.Please add at least one option to question'));
            return redirect()->back()->withInput();
        }
        if (isset($data->id)) {
            Session::flash('success', trans('language.Question is stored successfully'));
            return redirect()->route('questions.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Some error occured while storing question. Please try again.'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('questions.index', [$locale]), 'name' => "Questions"], ['name' => "Show"]
        ];

        $questionWithOptions = (new GetQuestionById)->execute($id);

        return view('admin.performance_review.questions.show')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questionWithOptions', $questionWithOptions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('questions.index', [$locale]), 'name' => "Questions"], ['name' => "Edit"]
        ];

        $questionWithOptions = (new GetQuestionById)->execute($id);

        return view('admin.performance_review.questions.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questionWithOptions', $questionWithOptions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateQuestionById)->execute($request);

        if ($data['options'] == 'null') {
            Session::flash('error', trans('language.Option field cannot be empty'));
            return redirect()->back();
        }
        if ($data['options'] == false) {
            Session::flash('error', trans('language.Please add at least one option to question'));
            return redirect()->back()->withInput();
        }
        if (isset($data->id)) {
            Session::flash('success', trans('language.Question is updated successfully'));
            return redirect()->route('questions.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Some went wrong while updating question. Please try again.'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $destroyQuestion = (new DestroyQuestionById)->execute($id);

        if ($destroyQuestion == true) {
            Session::flash('success', trans('language.Question is deleted successfully'));
            return redirect()->route('questions.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Some error occured while deleting question. Please try again.'));
            return redirect()->back()->withInput();
        }
    }
}
