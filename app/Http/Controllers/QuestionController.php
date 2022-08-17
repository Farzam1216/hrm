<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Hiring\Actions\DestroyQuestion;
use Session;
use Illuminate\Http\Request;
use App\Domain\Hiring\Models\Question;
use App\Domain\Hiring\Models\JobQuestion;
use App\Domain\Hiring\Actions\GetQuestion;
use App\Domain\Hiring\Actions\GetQuestionByID;
use App\Domain\Hiring\Actions\StoreQuestion;
use App\Domain\Hiring\Actions\UpdateQuestion;
use App\Domain\Hiring\Actions\StoreQuestionNotifications;
use App\Domain\Hiring\Actions\StoreQuestionUpdateNotifications;
use App\Domain\Hiring\Actions\StoreQuestionDeleteNotifications;

class QuestionController extends Controller
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
        $questions=(new GetQuestion())->execute();
        $breadcrumbs = [
            ['link' => "$locale/question", 'name' => "Settings | Hiring |  "], ['name' => "Canned Questions"]
        ];
        return view('admin.question.index')->with('ques', $questions)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/question", 'name' => "Settings | Hiring |  "], ['name' => "Add Canned Questions"]
        ];
        return view('admin.question.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/question", 'name' => "Settings | Hiring |  "], ['name' => "Edit Canned Questions"]
        ];
        $GetQuestion = (new GetQuestionByID())->execute($id);
        return view('admin.question.edit',
            ['GetQuestion' => $GetQuestion,
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
        /* Question::create(
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
        $response = (new StoreQuestion())->execute($request);
        if ($response === true) {
            (new StoreQuestionNotifications())->execute($request);
            Session::flash('success', trans('language.Question is created successfully'));
        } else {
            Session::flash('error', trans('language.Same Question already exist'));
        }
      

        return redirect($locale.'/questions')->with('locale', $locale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreQuestionUpdateNotifications())->execute($request, $id);
        (new UpdateQuestion())->execute($request, $id);
        Session::flash('success', trans('language.Question is Updated successfully'));
        return redirect($locale.'/questions')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreQuestionDeleteNotifications())->execute($id);
        (new DestroyQuestion())->execute($id);
        Session::flash('success', trans('language.Question is Deleted successfully'));
        return redirect($locale.'/questions')->with('locale', $locale);
    }
}
