<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceForm;

class DestroyFormById
{
    public function execute($id)
    {
        $form = PerformanceForm::find($id);

        if($form)
        {
            $form->delete();
            
            return true;
        }
        else
        {
            return false;
        }
    }
}
