<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\Task;

class DeleteTask
{
    public function execute($request,$id)
    {
//        $task = Tasks::find($id);
//        $task->delete();

        //if delete for new employees only then soft delete else hard delete - also maintain history
        if($request->delete_all == "true")
        {
            Task::where('id', $id)->forceDelete();
        }
        else
        {
            Task::where('id', $id)->delete();
        }
    }
}
