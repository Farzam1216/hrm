<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Domain\Poll\Actions\GetAllPolls;
use App\Domain\Poll\Actions\StorePoll;
use App\Domain\Poll\Actions\GetPollById;
use App\Domain\Poll\Actions\UpdatePoll;
use App\Domain\Poll\Actions\DeletePoll;
use App\Http\Requests\storePoll as StorePollRequest;


class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new GetAllPolls())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['name' => "Polls"]
        ];
        return view(
            'admin.polls.index',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'permissions'=> $data['permissions'],
                'polls' => $data['polls'],
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls", 'name' => "Polls"],
            ['name' => "Add Poll"]
        ];
        return view('admin.polls.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePollRequest $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $storePoll = (new StorePoll())->execute($request->all());
        if ($storePoll) {
            Session::flash('success', 'Poll is created successfully');
        } else {
            Session::flash('error', 'Poll creation is failed');
        }
        return redirect($locale . '/polls')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $poll = (new GetPollById())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls", 'name' => "Polls"],
            ['name' => "Show Poll"]
        ];
        return view(
            'admin.polls.show',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'poll' => $poll,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $poll = (new GetPollById())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Engagement Survey"], ['link' => "{$locale}/polls", 'name' => "Polls"],
            ['name' => "Edit Poll"]
        ];
        return view(
            'admin.polls.edit',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'poll' => $poll,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePollRequest $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $updatedPoll = (new UpdatePoll())->execute($request->all(), $id);
        if ($updatedPoll) {
            Session::flash('success', 'Poll is updated successfully');
        } else {
            Session::flash('error', 'Poll updating is failed');
        }
        return redirect($locale . '/polls')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deletedPoll = (new DeletePoll())->execute($id);
        if ($deletedPoll) {
            Session::flash('success', 'Poll is deleted successfully');
        } else {
            Session::flash('error', 'Poll deletion is failed');
        }
        return redirect($locale . '/polls')->with('locale', $locale);
    }


}
