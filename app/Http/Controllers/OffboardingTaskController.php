<?php

namespace App\Http\Controllers;

use App\Domain\Task\Actions\CreateNewTask;
use App\Domain\Task\Actions\DeleteTask;
use App\Domain\Task\Actions\EditTask;
use App\Domain\Task\Actions\GetOffboardingTasks;
use App\Domain\Task\Actions\GetOnboardingTasks;
use App\Domain\Task\Models\Task;
use App\Domain\Task\Models\TaskAttachmentTemplate;
use App\Domain\Task\Models\TaskRequiredForFilter;
use App\Http\Requests\validateTask;
use Illuminate\Http\Request;
use App\Domain\Task\Actions\AddEmployeeTask;
use App\Domain\Task\Actions\AddTask;
use App\Domain\Task\Actions\AssignDocument;
use App\Domain\Task\Actions\UpdateTask;
use Illuminate\Database\Eloquent\Model;
use App;
use Session;

class OffboardingTaskController extends Controller
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
        $tasks = (new GetOffboardingTasks())->execute();
        $breadcrumbs = [
            ['name' => "Settings"],['name' => 'Offboarding'],['name' => "Tasks"]
        ];

        return view('admin.task.index', [
            'breadcrumbs' => $breadcrumbs,
            'locale'=> $locale,
            'tasks'=> $tasks,
            'task_type' => "offboarding",
        ]);
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
        //0 for onboarding tasks
        $data = (new CreateNewTask())->execute(1);
        $breadcrumbs = [
            ['name' => "Settings"],['link' => "$locale/offboarding-tasks", 'name' => "Offboarding Tasks"], ['name' => "Add"]
        ];

        return view('admin.task.create')
            ->with(['locale'=> $locale, 'employees'=> $data['employees'],
                'taskCategory'=> $data['taskCategory'],
                'documents'=> $data['documents'],
                'departments' => $data['departments'],
                'locations'=> $data['locations'],
                'employmentStatus' => $data['employmentStatus'],
                'designations'=> $data['designations'],
                'divisions'=> $data['divisions'],
                'breadcrumbs'=> $breadcrumbs,
                'task_type'=> "offboarding"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(validateTask $request)
    {

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        (new AddTask())->execute($request, 1);

        Session::flash('success', trans('Task template is created successfully'));

        return redirect($locale.'/offboarding-tasks')->with('locale', $locale);
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
    public function edit(Request $request,$lang, $id)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditTask())->execute($id, 1);

        $breadcrumbs = [
            ['name' => "Settings"],['link' => "$locale/offboarding-tasks", 'name' => "Offboarding Tasks"], ['name' => "Edit"]
        ];
        return view('admin.task.edit')
            ->with('task', $data['task'])
//            ->with('departmentCheck', $data['departmentCheck'])
//            ->with('locationCheck', $data['locationCheck'])
//            ->with('employmentStatusCheck', $data['employmentStatusCheck'])
//            ->with('employeeTask', $data['employeeTask'])
            ->with('taskDocuments', $data['taskDocuments'])
            ->with('documents', $data['documents'])
            ->with('employees', $data['employees'])
            ->with('taskCategory', $data['taskCategory'])
            ->with('departments', $data['departments'])
            ->with('taskDepartments', $data['taskDepartments'])
            ->with('locations', $data['locations'])
            ->with('taskLocations', $data['taskLocations'])
            ->with('employmentStatus', $data['employmentStatus'])
            ->with('taskEmploymentStatus', $data['taskEmploymentStatus'])
            ->with('designations', $data['designations'])
            ->with('taskDesignations', $data['taskDesignations'])
            ->with('divisions', $data['divisions'])
            ->with('taskDivisions', $data['taskDivisions'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('task_type', "offboarding");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang,validateTask $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateTask())->execute($request, $id, 0);

        Session::flash('success', trans('Task is Updated successfully'));
        return redirect($locale.'/offboarding-tasks')->with('locale', $locale);    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
         (new DeleteTask())->execute($request,$id);

        Session::flash('success', trans('Task is Deleted successfully'));
        return redirect($locale.'/offboarding-tasks')->with('locale', $locale);
    }
}
