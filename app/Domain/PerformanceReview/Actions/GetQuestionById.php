<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;
use App\Domain\PerformanceReview\Models\PerformanceQuestionOption;

class GetQuestionById
{
    public function execute($id)
    {
        $questionWithOptions = PerformanceQuestion::where('id', $id)->with('options')->get();

        return $questionWithOptions;
    }
}
