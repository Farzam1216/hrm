<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Task\Actions\AddTask;
use App\Domain\Task\Actions\CreateNewTask;
use App\Domain\Task\Actions\DeleteTask;
use App\Domain\Task\Actions\DeleteTaskDocument;
use App\Domain\Task\Actions\EditTask;
use App\Domain\Task\Actions\GetTask;
use App\Domain\Task\Actions\UpdateTask;
use App\Http\Requests\validateTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $tasks = (new GetTask())->execute();
        $breadcrumbs = [
            ['link' => "/tasks", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Tasks"]
        ];

        return view('admin.task.index', [
            'breadcrumbs' => $breadcrumbs,
            'locale'=> $locale,
            'tasks'=> $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new CreateNewTask())->execute();
        $breadcrumbs = [
            ['link' => "$locale/tasks", 'name' => "Settings"], ['link' => "$locale/tasks", 'name' => "Tasks"], ['link' => "$locale/tasks", 'name' => "Task"], ['name' => "Add Task"]
        ];
        return view('admin.task.create')
            ->with('locale', $locale)
            ->with('employees', $data['employee'])
            ->with('taskCategory', $data['taskCategory'])
            ->with('documents', $data['document'])
            ->with('departments', $data['department'])
            ->with('locations', $data['locations'])
            ->with('employmentStatus', $data['employmentStatus'])
            ->with('breadcrumbs', $breadcrumbs);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param validateTask $request
     *
     * @return RedirectResponse
     */
    public function store(validateTask $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        (new AddTask())->execute($request->all());

        Session::flash('success', trans('Task is created successfully'));

        return redirect($locale.'/tasks')->with('locale', $locale);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $lang
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function edit($lang, Request $request, $id)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditTask())->execute($id);
        $breadcrumbs = [
            ['link' => "$locale/tasks", 'name' => "Settings"], ['link' => "$locale/tasks", 'name' => "Tasks"], ['link' => "$locale/tasks", 'name' => "Task"], ['name' => "Edit Task"]
        ];
        return view('admin.task.edit')
            ->with('task', $data['task'])
            ->with('departmentCheck', $data['departmentCheck'])
            ->with('locationCheck', $data['locationCheck'])
            ->with('employmentStatusCheck', $data['employmentStatusCheck'])
            ->with('employeeTask', $data['employeeTask'])
            ->with('taskDocuments', $data['taskDocument'])
            ->with('employees', $data['employee'])
            ->with('taskCategory', $data['taskCategory'])
            ->with('departments', $data['department'])
            ->with('locations', $data['locations'])
            ->with('employmentStatus', $data['employmentStatus'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param validateTask $request
     * @param int          $id
     *
     * @return void
     */
    public function update($lang, validateTask $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateTask())->execute($request->all(), $id);
        Session::flash('success', trans('Task is Updated successfully'));
        return redirect($locale.'/tasks')->with('locale', $locale);
    }

    /**
     *
     */
    public function show()
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteTask())->execute($id);
        Session::flash('success', trans('Task is Deleted successfully'));
        return redirect($locale.'/tasks')->with('locale', $locale);
    }




    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroyTaskTemplate(Request $request)
    {
        $taskDocumentId = $request->id;
        (new DeleteTaskDocument())->execute($taskDocumentId);

        return response()->json($taskDocumentId);
    }
}
