<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Domain\Poll\Actions\GetPollById;
use App\Domain\Poll\Actions\GetAllQuestionOption;
use App\Domain\Poll\Actions\GetAllPollQuestions;
use App\Domain\Poll\Actions\StorePollQuestion;
use App\Domain\Poll\Actions\StorePollQuestionOptions;
use App\Domain\Poll\Actions\DeletePollQuestion;
use App\Domain\Poll\Actions\UpdatePollQuestion;
use App\Http\Requests\StorePollQuestion as StorePollQuestionRequest;
use Session;


class PollQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $locale, $pollId)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new GetAllPollQuestions())->execute($pollId);
        if (empty($data['poll'])) {
            abort(404);
        }
        $pollQuestions = $data['pollQuestions'];
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls", 'name' => "Polls"],
            ['name' => "Poll Questions"]
        ];
        return view(
            'admin.poll_questions.index',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'pollQuestions' => $pollQuestions,
                'pollId' => $pollId,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $locale, $pollId)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $poll = (new GetPollById())->execute($pollId);
        if (empty($poll)) {
            abort(404);
        }
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls/$pollId/questions", 'name' => "Poll Questions"],
            ['name' => "Create Questions"]
        ];
        return view(
            'admin.poll_questions.create',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'poll' => $poll,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePollQuestionRequest $request, $locale, $pollId)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $questionId = (new StorePollQuestion())->execute($request->all(), $pollId);
        if ($questionId) {

            $options = (new StorePollQuestionOptions())->execute($request->all(), $pollId, $questionId);

            if ($options) {
                Session::flash('success', 'Question is created successfully');
            } else {
                Session::flash('error', 'Question creation is failed');
            }
        } else {
            Session::flash('error', 'Question creation is failed');
        }

        return redirect()->route('polls.questions.index', [$locale, $pollId]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $pollId, $questionId)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $question = (new GetAllQuestionOption())->execute($questionId);

        if (count($question) == 0) {
            abort(404);
        }

        $questionTitle = $question[0]['title'];
        $options = $question[0]['options'];

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls/$pollId/questions", 'name' => "Poll Questions"],
            ['name' => "Edit Questions"]
        ];
        return view(
            'admin.poll_questions.edit',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'options' => $options,
                'pollId' => $pollId,
                'questionTitle' => $questionTitle,
                'questionId' => $questionId,

            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(StorePollQuestionRequest $request, $locale, $pollId, $questionId)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $updatedPollQuestion = (new UpdatePollQuestion())->execute($request->all(), $pollId, $questionId);

        if ($updatedPollQuestion) {
            Session::flash('success', 'Question is updated successfully');
        } else {
            Session::flash('error', 'Question updating is failed');
        }
        return redirect()->route('polls.questions.edit', [$locale, $pollId, $questionId]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $pollId, $questionId)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deletedPoll = (new DeletePollQuestion())->execute($questionId);
        if ($deletedPoll) {
            Session::flash('success', 'Question is deleted successfully');
        } else {
            Session::flash('error', 'Question deletion is Failed');
        }
        return redirect()->route('polls.questions.index', [$locale, $pollId]);
    }
}
