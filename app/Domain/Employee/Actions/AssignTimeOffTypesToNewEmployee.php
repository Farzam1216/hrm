<?php


namespace App\Domain\Employee\Actions;

use Illuminate\Support\Facades\Artisan;
use Session;

class AssignTimeOffTypesToNewEmployee
{
    public function execute($employee_id)
    {
        try {
            Artisan::call('assign:timeofftype', ['id' => $employee_id]);
            return true;
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return false;
        }
    }
}
