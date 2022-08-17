<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateNotes;
use App\Domain\Employee\Actions\DeleteNotes;
use App\Domain\Employee\Actions\UpdateNotes;
use App\Domain\Employee\Actions\ViewEmployeeNotes;
use Illuminate\Http\Request;
use Session;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $employee_id)
    {
        $notes = (new ViewEmployeeNotes())->execute($employee_id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.notes.index')->with('notes', $notes)->with('employee_id', $employee_id)->with('locale', $locale);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data =(new CreateNotes())->execute($request->all());
        Session::flash('success', trans('language.Note added successfully'));

        return redirect()->back()->with('notes', $data['notes'])->with('employee_id', $data['employeeId'])->with('locale', $locale);
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
    public function update(Request $request, $note_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new UpdateNotes())->execute($request->all(), $note_id);
        Session::flash('success', trans('language.Note updated successfully'));

        return redirect()->back()->with('notes', $data['notes'])->with('employee_id', $data['employeeId'])->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $note_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $note_id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new DeleteNotes())->execute($request->all(), $note_id);
        Session::flash('success', trans('language.Note deleted successfully'));

        return redirect()->back()->with('notes', $data['notes'])->with('employee_id', $data['employeeId'])->with('locale', $locale);
    }
}
