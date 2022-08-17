<?php

namespace App\Http\Controllers;

use App\Domain\Task\Actions\AddTaskCategory;
use App\Domain\Task\Actions\DeleteTaskCategory;
use App\Domain\Task\Actions\GetOffboardingTaskCategories;
use App\Domain\Task\Actions\GetTaskCategoryByID;
use App\Domain\Task\Actions\UpdateTaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;

class OffboardingCategoryController extends Controller
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
        $taskCategories = (new GetOffboardingTaskCategories())->execute();
        $breadcrumbs = [
            ['name' => "Settings"],['name' => 'Offboarding'],['name' => "Categories"]
        ];
        $task_type = "offboarding";
        return view('admin.task_category.index', compact('taskCategories', 'breadcrumbs', 'locale', 'task_type'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['name' => "Settings"],['name' => 'Offboarding'],['link' => "$locale/offboarding-categories",'name' => "Categories"], ['name' => "Add Category"]
        ];

        return view('admin.task_category.create', ['breadcrumbs' => $breadcrumbs,
            'task_type' => "offboarding",
            'locale' => $locale]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1 means offboarding tasks
        (new AddTaskCategory())->execute($request, 1);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('Category is Added successfully'));
        return redirect($locale . '/offboarding-categories')->with('locale', $locale);
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
    public function edit(Request $request,$lang,$id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['name' => "Settings"],['name' => 'Offboarding'],['link' => "$locale/offboarding-categories",'name' => "Categories"], ['name' => "Edit Category"]
        ];

        $task_category = (new GetTaskCategoryByID())->execute($id);
        return view('admin.task_category.edit',
            ['task_category' => $task_category,
                'breadcrumbs' => $breadcrumbs,
                'task_type' => 'offboarding',
                'locale' => $locale
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateTaskCategory())->execute($request, $id);
        Session::flash('success', trans('Category is updated successfully'));
        return redirect($locale.'/offboarding-categories')->with('locale', $locale);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$lang, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteTaskCategory())->execute($id);
        Session::flash('success', trans('Category is deleted successfully'));
        return redirect($locale.'/offboarding-categories')->with('locale', $locale);


    }
}
