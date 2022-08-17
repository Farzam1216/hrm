<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Note;
use Illuminate\Support\Facades\Auth;

class CreateNotes
{

    /**
     * @param $data
     *
     * @return array
     */
    public function execute($data)
    {
        $note = new Note();
        $employeeId = $data['employee_id'];
        $note->username = Auth::user()->firstname;
        $note->note = $data['note'];
        $note->employee_id = $data['employee_id'];
        $note->save();
        $notes = Note::where('employee_id', $data['employee_id'])->get();
        $data = [
            'notes' => $notes,
            'employeeId' => $employeeId,
        ];

        return $data;
    }
}
