<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Task\Actions\GetTaskCategoryByID;
use Session;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\validateTaskCategory;
use App\Domain\Task\Actions\AddTaskCategory;
use App\Domain\Task\Actions\DeleteTaskCategory;
use App\Domain\Task\Actions\UpdateTaskCategory;
use App\Domain\Task\Actions\GetAllTaskCategories;

class TaskCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $taskCategories = (new GetAllTaskCategories())->execute();
        $breadcrumbs = [
            ['link' => "$locale/task-categories", 'name' => "Settings | Tasks"], ['name' => ""]
        ];
        return view('admin.task_category.index', compact('taskCategories', 'breadcrumbs', 'locale'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/task-categories", 'name' => "Settings | Tasks | Task Category |"], ['name' => "Add Task Category"]
        ];
        return view('admin.task_category.create', ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(validateTaskCategory $request)
    {
        (new AddTaskCategory())->execute($request);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('Category is Added successfully'));
        return redirect($locale . '/task-categories')->with('locale', $locale);
    }

    /**
     * @param $lang
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/task-categories", 'name' => "Settings | Types | Task Category |"], ['name' => "Edit Task Category"]
        ];
        $task_category = (new GetTaskCategoryByID())->execute($id);
        return view('admin.task_category.edit',
            ['task_category' => $task_category,
                'breadcrumbs' => $breadcrumbs,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(validateTaskCategory $request,$lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateTaskCategory())->execute($request, $id);
        Session::flash('success', trans('Category is updated successfully'));
        return redirect($locale.'/task-categories')->with('locale', $locale);

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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$lang, $id)
    {
         $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteTaskCategory())->execute($id);
        Session::flash('success', trans('Category is deleted successfully'));
        return redirect($locale.'/task-categories')->with('locale', $locale);


    }
}