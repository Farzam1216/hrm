<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Hiring\Actions\DestroyeQuestionType;
use Session;
use Illuminate\Http\Request;
use App\Domain\Hiring\Actions\GetQuestionType;
use App\Domain\Hiring\Actions\GetQuestionTypeByID;
use App\Domain\Hiring\Actions\StoreQuestionType;
use App\Domain\Hiring\Actions\UpdateQuestionType;
use App\Domain\Hiring\Actions\StoreQuestionTpyeNotifications;
use App\Domain\Hiring\Actions\StoreQuestionTpyeUpdateNotifications;
use App\Domain\Hiring\Actions\StoreQuestionTypeDeleteNotifications;

class QuestionTypeController extends Controller
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
        $questionTypes=(new GetQuestionType())->execute();
        $breadcrumbs = [
            ['link' => "$locale/questiontype", 'name' => "Settings | Hiring |  "], ['name' => "Question Types"]
        ];

        return view('admin.question_type.index')->with('questionTypes', $questionTypes)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/question-types", 'name' => "Settings | Hiring |  "], ['name' => "Add Question Types"]
        ];
        return view('admin.question_type.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/question-types", 'name' => "Settings | Hiring |  "], ['name' => "Edit Job Questions"]
        ];
        $GetQuestionType = (new GetQuestionTypeByID())->execute($id);
        return view('admin.question_type.edit',
            ['GetQuestionType' => $GetQuestionType,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
            ]);
    }


    /**
     *  Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        /*Question::create(
             [
                 'question' => 'Resume',
                 'fieldType' => 'file',
                 'type_id' => '1'

             ]
             );
             Question::create(
                 [
                     'question' => 'Twitter URL',
                     'fieldType' => 'text',
                     'type_id' => '1'

                 ]
                 );
                 Question::create(
                     [
                         'question' => 'Cover Image',
                         'fieldType' => 'file',
                         'type_id' => '1'

                     ]
                     );*/


        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $response = (new StoreQuestionType())->execute($request);
        if ($response === true) {
            (new StoreQuestionTpyeNotifications())->execute($request);
            Session::flash('success', trans('language.Question Type is created successfully'));
        } else {
            Session::flash('error', trans('language.Question Type with this name already exist'));
        }
        return redirect($locale . '/question-types')->with('locale', $locale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang, Request $request, $id)
    {
        //
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreQuestionTpyeUpdateNotifications())->execute($request,$id);
        (new UpdateQuestionType())->execute($request,$id);
        Session::flash('success', trans('language.Questions Type is Updated successfully'));
        return redirect($locale . '/question-types')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreQuestionTypeDeleteNotifications())->execute($id);
        (new DestroyeQuestionType())->execute($id);

        Session::flash('success', trans('language.Questions Type is Deleted successfully'));

        return redirect($locale . '/question-types')->with('locale', $locale);
    }
}
