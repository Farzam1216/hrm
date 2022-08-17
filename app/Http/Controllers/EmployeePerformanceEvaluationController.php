<?php

namespace App\Http\Controllers;

use App;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\PerformanceReview\Actions\StoreEvaluation;
use App\Domain\PerformanceReview\Actions\GetQuestionsWithOptions;
use App\Domain\PerformanceReview\Actions\UpdateEvaluationById;
use App\Domain\PerformanceReview\Actions\DestroyEvaluationById;
use App\Domain\PerformanceReview\Actions\StoreEvaluationDecision;
use App\Domain\PerformanceReview\Actions\GetEvaluationsByEmployeeId;
use App\Domain\PerformanceReview\Actions\StoreEvaluationAssignment;
use App\Domain\PerformanceReview\Actions\GetEmployeesWithEvaluations;
use App\Domain\PerformanceReview\Actions\GetEvaluationWithQuestionsById;
use App\Domain\PerformanceReview\Actions\GetEmployeesWithAssignedEvaluations;

class EmployeePerformanceEvaluationController extends Controller
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

        $data = (new GetEmployeesWithEvaluations)->execute();
        $authEmployeeQuestionnaires = (new GetEvaluationsByEmployeeId)->execute(Auth::id());
        $date = Carbon::now();

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['name' => "Evaluations"]
        ];

        return view('admin.performance_review.evaluation.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employees', $data['employees'])
        ->with('assignments', $data['assignments'])
        ->with('permissions', $data['permissions'])
        ->with('authEmployeeQuestionnaires', $authEmployeeQuestionnaires['questionnaires'])
        ->with('currentYear', $date->year);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeEvaluations(Request $request, $locale, $employee_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['name' => "Employee Evaluations"]
        ];

        $data = (new GetEvaluationsByEmployeeId)->execute($employee_id);

        return view('admin.performance_review.evaluation.employee_evaluations')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee', $data['employee'])
        ->with('permissions', $data['permissions'])
        ->with('questionnaires', $data['questionnaires']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fill(Request $request, $locale, $employee_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['name' => "Fill"]
        ];

        $data = (new GetQuestionsWithOptions)->execute($employee_id);
        if ($data == false) {
            Session::flash('error', trans('language.Questionnaire form is not assigned to employee'));
            return back();
        }

        return view('admin.performance_review.evaluation.fill')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee_id', $employee_id)
        ->with('questions', $data['questions'])
        ->with('employee', $data['employee']);
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

        $data = (new StoreEvaluation)->execute($request);
        Session::flash('success', trans('language.Evaluation is stored successfully'));

        return redirect()->route('evaluations.index', [$locale]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $questionnaire_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['name' => "Show"]
        ];

        $data = (new GetEvaluationWithQuestionsById)->execute($questionnaire_id);

        return view('admin.performance_review.evaluation.show')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('questions', $data['questions'])
        ->with('questionsHistory', $data['questionsHistory'])
        ->with('optionsHistory', $data['optionsHistory'])
        ->with('permissions', $data['permissions'])
        ->with('questionnaire', $data['questionnaire']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $employee_id, $questionnaire_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['link' => route('evaluations.employee-evaluations', [$locale, $employee_id]), 'name' => "Employee Evaluations"], ['name' => "Edit"]
        ];

        $data = (new GetEvaluationWithQuestionsById)->execute($questionnaire_id);

        return view('admin.performance_review.evaluation.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee_id', $employee_id)
        ->with('questions', $data['questions'])
        ->with('questionsHistory', $data['questionsHistory'])
        ->with('optionsHistory', $data['optionsHistory'])
        ->with('questionnaire_id', $questionnaire_id)
        ->with('questionnaire', $data['questionnaire']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateEvaluationById)->execute($request);
        Session::flash('success', trans('language.Evaluation is submitted successfully'));

        return redirect()->route('evaluations.employee-evaluations', [$locale, $request->employee_id]);
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

        $destroyQuestionnaire = (new DestroyEvaluationById)->execute($id);

        if ($destroyQuestionnaire == true) {
            Session::flash('success', trans('language.Evaluation is deleted successfully'));
            return redirect()->route('evaluations.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Some error occured while deleting evaluation. Please try again.'));
            return redirect()->back()->withInput();
        }
    }

    public function decision(Request $request, $locale, $employee_id, $questionnaire_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['link' => route('evaluations.employee-evaluations', [$locale, $employee_id]), 'name' => "Employee Evaluations"], ['name' => "Decision"]
        ];

        $data = (new GetEvaluationWithQuestionsById)->execute($questionnaire_id);

        return view('admin.performance_review.evaluation.decision')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee_id', $employee_id)
        ->with('questionnaire_id', $questionnaire_id)
        ->with('questions', $data['questions'])
        ->with('questionsHistory', $data['questionsHistory'])
        ->with('optionsHistory', $data['optionsHistory'])
        ->with('questionnaire', $data['questionnaire']);
    }

    public function submitDecision(Request $request, $locale, $employee_id, $questionnaire_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreEvaluationDecision)->execute($request);
        Session::flash('success', trans('language.Decision against evaluation is submitted successfully'));

        return redirect()->route('evaluations.employee-evaluations', [$locale, $employee_id]);
    }

    public function assign(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['name' => "Assign"]
        ];

        $data = (new GetEmployeesWithAssignedEvaluations)->execute();

        return view('admin.performance_review.evaluation.assign')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employees', $data['employees']);
    }

    public function submitAssignment(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new StoreEvaluationAssignment)->execute($request);

        if ($data['employee'] == true && $data['check'] == 'assigned') {
            Session::flash('success', 'Evaluation is assigned to respected managers of employees successfully');
        }
        if ($data['employee'] == true && $data['check'] == 'updated') {
            Session::flash('success', 'Evaluation assignment is updated successfully');
        }
        if($data['employee'] == false) {
            Session::flash('error', 'Manager is not assigned to employee '. $data['employee_name'] . '. Please assign manager and then try again.');
        }

        return response()->json($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $locale, $employee_id, $questionnaire_id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Performance Review"], ['link' => route('evaluations.index', [$locale]), 'name' => "Evaluations"], ['link' => route('evaluations.employee-evaluations', [$locale, $employee_id]), 'name' => "Employee Evaluations"], ['name' => "Show"]
        ];

        $data = (new GetEvaluationWithQuestionsById)->execute($questionnaire_id);

        return view('admin.performance_review.evaluation.show')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee_id', $employee_id)
        ->with('questions', $data['questions'])
        ->with('questionsHistory', $data['questionsHistory'])
        ->with('optionsHistory', $data['optionsHistory'])
        ->with('permissions', $data['permissions'])
        ->with('questionnaire', $data['questionnaire']);
    }
}
