<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateNotes
{
    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function execute($data, $id)
    {
        $note = Note::find($id);
        $note->username = Auth::user()->firstname;
        $employeeId = $data['employee_id'];
        $note->note = $data['note'];
        $note->employee_id = $data['employee_id'];
        $note->save();
        $notes = Note::where('employee_id', $data['employee_id'])->get();
        $data = [
            'employeeId' => $employeeId,
            'notes' => $notes,
        ];

        return $data;
    }
}
