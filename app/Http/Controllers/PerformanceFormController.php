<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\PerformanceReview\Actions\GetQuestions;
use App\Domain\PerformanceReview\Actions\GetForms;
use App\Domain\PerformanceReview\Actions\StoreForm;
use App\Domain\PerformanceReview\Actions\UpdateFormById;
use App\Domain\PerformanceReview\Actions\DestroyFormById;
use App\Domain\PerformanceReview\Actions\StoreFormAssignment;
use App\Domain\PerformanceReview\Actions\GetEmployeesWithForm;
use App\Domain\PerformanceReview\Actions\GetFormWithQuestionsById;

class PerformanceFormController extends Controller
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
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['name' => " Forms"]
        ];

        $forms = (new GetForms)->execute();

        return view('admin.performance_review.forms.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('forms', $forms);
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
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('forms.index', [$locale]), 'name' => "Forms"], ['name' => "Add"]
        ];

        $questions = (new GetQuestions)->execute();

        return view('admin.performance_review.forms.create')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questions', $questions);
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

        $data = (new StoreForm)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Form is stored successfully'));
        } else {
            Session::flash('error', trans('language.Something went wrong while storing form. Please try again.'));
        }

        return redirect()->route('forms.index', [$locale]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('forms.index', [$locale]), 'name' => "Forms"], ['name' => "Show"]
        ];

        $data = (new GetFormWithQuestionsById)->execute($id);

        return view('admin.performance_review.forms.show')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questions', $data['questions'])
        ->with('performanceForm', $data['performanceForm']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('forms.index', [$locale]), 'name' => "Forms"], ['name' => "Edit"]
        ];

        $data = (new GetFormWithQuestionsById)->execute($id);

        return view('admin.performance_review.forms.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questions', $data['questions'])
        ->with('performanceForm', $data['performanceForm']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateFormById)->execute($request, $id);

        if ($data) {
            Session::flash('success', trans('language.Form is updated successfully'));
        } else {
            Session::flash('error', trans('language.Something went wrong while updating form. Please try again.'));
        }

        return redirect()->route('forms.index', [$locale]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $destroy = (new DestroyFormById)->execute($id);

        if ($destroy == true) {
            Session::flash('success', trans('language.Form is deleted successfully'));
            return redirect()->route('forms.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Some error occured while deleting form. Please try again.'));
            return redirect()->back()->withInput();
        }
    }

    public function assign(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('forms.index', [$locale]), 'name' => "Forms"], ['name' => "Assign"]
        ];

        $data = (new GetEmployeesWithForm)->execute($id);

        return view('admin.performance_review.forms.assign')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('form', $data['form'])
        ->with('employees', $data['employees']);
    }

    public function submitAssignment(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new StoreFormAssignment)->execute($request);

        if ($data['employee'] == true && $data['check'] == 'assigned') {
            Session::flash('success', 'Form is assigned to employees successfully');
        }
        if ($data['employee'] == true && $data['check'] == 'updated') {
            Session::flash('success', 'Form assignment is updated successfully');
        }

        return response()->json($request);
    }
}
