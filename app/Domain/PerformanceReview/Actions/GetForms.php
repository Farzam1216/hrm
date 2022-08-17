<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceForm;

class GetForms
{
    public function execute()
    {
        $forms = PerformanceForm::all();

        return $forms;
    }
}
