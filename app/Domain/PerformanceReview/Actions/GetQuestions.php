<?php


namespace App\Domain\PerformanceReview\Actions;

use App\Domain\PerformanceReview\Models\PerformanceQuestion;

class GetQuestions
{
    public function execute()
    {
        $questions = PerformanceQuestion::all();

        return $questions;
    }
}
