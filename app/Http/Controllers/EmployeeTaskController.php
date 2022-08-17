<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\ViewEmployeeTasks;
use App\Domain\Task\Actions\EmployeeTaskcompletedstatus;
use App\Domain\Task\Actions\EmployeeTaskIncompletedStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $lang
     * @param $employeeID
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($lang, $employeeID, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new ViewEmployeeTasks())->execute($employeeID, $request, $this);

        return view('admin.task.mytask')->with('locale', $locale)->with('employeesTaskFor', $data['employeesTaskFor'])->with('employee', $data['employee'])->with('completedStatusTasks', $data['completedStatusTasks']);
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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function completedstatusAJAX(Request $request)
    {
        $data = (new EmployeeTaskCompletedStatus())->execute($request->all());
        return response()->json($data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function incompletedstatusAJAX(Request $request)
    {
        $data = (new EmployeeTaskIncompletedStatus())->execute($request->all());
        return response()->json($data);
    }
}
