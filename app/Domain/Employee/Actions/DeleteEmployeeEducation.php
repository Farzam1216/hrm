<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Education;
use Illuminate\Support\Facades\Log;
use Session;

class DeleteEmployeeEducation
{
    public function execute($id)
    {
        try {
            $education = Education::find($id);
            $education->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
