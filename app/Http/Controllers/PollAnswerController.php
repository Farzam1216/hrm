<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Domain\Poll\Actions\GetAllPollQuestions;
use App\Domain\Poll\Actions\StorePollAnswers;
use App\Domain\Poll\Actions\GetPollAnswerWithId;
use App\Http\Requests\StorePollAnswer as StorePollAnswerRequest;
use Auth;




class PollAnswerController extends Controller
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
    public function create(Request $request, $locale, $pollId)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employeeId = Auth::id();
        $pollAttempted = (new GetPollAnswerWithId())->execute($pollId, $employeeId);
        if (!empty($pollAttempted)) {
            abort(404);
        }

        $data = (new GetAllPollQuestions())->execute($pollId);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls", 'name' => "Polls"],
            ['name' => "Take Poll"]
        ];
        // return $data;
        return view(
            'admin.poll_answers.create',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'poll' => $data['poll'],
                'questions' => $data['pollQuestions'],
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePollAnswerRequest $request, $locale, $pollId)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employeeId = Auth::id();
        $pollAttempted = (new GetPollAnswerWithId())->execute($pollId, $employeeId);
        if (!empty($pollAttempted)) {
            abort(404);
        }

        $storeAnswers = (new StorePollAnswers())->execute($request->all(), $pollId);
        if ($storeAnswers) {
            Session::flash('success', 'Poll is submitted successfully');
        } else {
            Session::flash('error', 'Poll submittion is failed');
        }
        return redirect($locale . '/polls')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PollAnswer  $pollAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(PollAnswer $pollAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PollAnswer  $pollAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(PollAnswer $pollAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PollAnswer  $pollAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PollAnswer $pollAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PollAnswer  $pollAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(PollAnswer $pollAnswer)
    {
        //
    }

    public function showAttemptPoll(Request $request, $id)
    {
    }
}
