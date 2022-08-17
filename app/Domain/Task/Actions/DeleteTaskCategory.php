<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\TaskCategory;
use App\Domain\Task\Models\Task;
use Session;

class DeleteTaskCategory
{
    public function execute($id)
    {
        $taskCategory = TaskCategory::find($id);
        $tasks = Task::where('category', $id)->get();
        if ($tasks->isNotEmpty()) {
            // Session::flash('error', trans('language.Failed to delete. Delete tasks related to this Task Category first.'));
            Task::where('category', $id)->update(['category' => null]);
//            return false;
        }
            $taskCategory->delete();
            return true;
            // Session::flash('success', trans('language.Task Category is Deleted successfully'));
    }
}
