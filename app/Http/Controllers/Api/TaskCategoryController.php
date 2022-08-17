<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Domain\Task\Actions\AddTaskCategory;
use App\Domain\Task\Actions\DeleteTaskCategory;
use App\Domain\Task\Actions\UpdateTaskCategory;
use App\Domain\Task\Actions\GetAllTaskCategories;

class TaskCategoryController extends Controller
{
    use ApiResponseTrait;
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $taskCategories = (new GetAllTaskCategories())->execute();
        return response()->json([
            'taskCategories'=> $taskCategories,
        ],200);
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
    public function store(Request $request)
    {
        $msg = (new AddTaskCategory())->execute($request);
        if ($msg) {
            return response()->json([
                'msg' => 'Task Category has been created successfully','success'=> true
            ]);
        }else {
            return response()->json([
                'msg' => 'Task Category with this name already exist','success'=> false
            ]);
        }
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
    public function update(Request $request, $id)
    {
        $response = (new UpdateTaskCategory())->execute($request, $id);
        if ($response) {
            return response()->json([
                'msg' => 'Task Category has been updated successfully','success'=> true,
                'taskCategories' => $response
            ]);
        }else {
            return response()->json([
                'msg' => 'Failed to update.','success'=> false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = (new DeleteTaskCategory())->execute($id);
        if ($response) {
            return response()->json([
                'msg' => 'Task Category has been deleted successfully','success'=> true
            ]);
        }else {
            return response()->json([
                'msg' => 'Failed to delete. Delete tasks related to this Task Category first.','success'=> false
            ]);
        }
    }
}
