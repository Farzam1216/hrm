<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Note;
use Illuminate\Http\Request;

class DeleteNotes
{
    /**
     * @param Request $request
     * @param $id
     *
     * @return array
     */
    public function execute($data, $id)
    {
        $note = Note::find($id);
        $note->delete();
        $employeeId = $data['employee_id'];
        $notes = Note::where('employee_id', $employeeId)->get();
        $data = [
            'employeeId' => $employeeId,
            'notes' => $notes,
        ];

        return $data;
    }
}
