<?php


namespace App\Domain\Employee\Actions;

use Illuminate\Support\Facades\Artisan;

use Session;

class AssignOnboardingTasksToNewEmployee
{
    public function execute()
    {
        try {
            Artisan::call('assign:task');
            Session::flash('success', trans('language.On Boarding Task Assgined successfully'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
    }
}
