<?php


namespace App\Domain\Hiring\Actions;

use App\Domain\Hiring\Models\Question;
use Illuminate\Support\Facades\DB;

class GetCandidateAnswers
{
    public function execute()
    {
        $count = 0;
        $answers= null;
        $candidate = [];
        if (isset($candidate['answers']) && count($candidate['answers'])) {
            foreach ($candidate['answers'] as $answer) {
                /*-----If Question Link Found in Job Questions Table----*/
                if ($answer->jobQuestion) {
                    $jobQue = $answer->jobQuestion;
                    if (
                        $jobQue['job_id'] == $candidate['candidate']->job_id
                        && $jobQue['id'] == $answer->jobQuestions_id
                    ) {
                        if ($jobQue['question']) {
                            $question_id = $jobQue['question']->id;
                            /*------Scenario 1 - If Question is Edited---------*/
                            if ($jobQue['question']->updated_at >= $answer->created_at) {
                                $historyCollection = DB::table('question_histories')
                                ->where('que_id', $question_id)
                                    ->orderBy('created_at', 'asc')->get();
                                foreach ($historyCollection as $queHistory) {
                                    if ($queHistory->created_at >= $answer->created_at) {
                                        $answers[$count]['que'] = $queHistory->que_desc;
                                        $answers[$count]['type'] = $queHistory->que_type;
                                        $answers[$count]['field'] = $queHistory->que_field;
                                        $answers[$count]['answer'] = $answer->answer;
                                        $count++;
                                        break;
                                    }
                                }
                            } /*------Scenario 2 - Question is not edited----*/ else {
                                $answers[$count]['que'] = $jobQue['question']->question;
                                $answers[$count]['type'] = $jobQue['question']->type_id;
                                $answers[$count]['field'] = $jobQue['question']->fieldType;
                                $answers[$count]['answer'] = $answer->answer;
                                $count++;
                            }
                        } /*----Scenario 3 - Question is in History Table----*/ else {
                            $question_id = $jobQue['que_id'];
                            $historyCollection = DB::table('question_histories')
                            ->where('que_id', $question_id)
                                ->orderBy('created_at', 'asc')->get();
                            foreach ($historyCollection as $queHistory) {
                                if ($queHistory->created_at >= $answer->created_at) {
                                    $answers[$count]['que'] = $queHistory->que_desc;
                                    $answers[$count]['type'] = $queHistory->que_type;
                                    $answers[$count]['field'] = $queHistory->que_field;
                                    $answers[$count]['answer'] = $answer->answer;
                                    $count++;
                                    break;
                                }
                            }
                        }
                    }
                } /*-----If Questions Link not found in JOB Questions------*/ else {
                    /*--Job Questions History table can have many verisons of job Questions--*/
                    $jobque_id = $answer->jobQuestions_id;
                    $jobQuehistoryCollection = DB::table('job_question_histories')
                    ->where('jobque_id', $jobque_id)
                        ->orderBy('created_at', 'asc')
                        ->get();
                    if ($jobQuehistoryCollection) {
                        foreach ($jobQuehistoryCollection as $jobQue) {
                            if ($answer->created_at < $jobQue->created_at) {
                                $question_id = $jobQue->que_id;
                                /*----------Scenario 5 - Question is edited
                                so it can be inside Questions table
                                if it is a Canned Question-------*/
                                $questionsTable = Question::where('id', $question_id)
                                    ->first();
                                if ($questionsTable) {
                                    // Question exists in Question Table
                                    if ($questionsTable->updated_at >= $answer->created_at) {
                                        $historyCollection = DB::table('question_histories')->where('que_id', $question_id)->orderBy('created_at', 'asc')->get();
                                        foreach ($historyCollection as $que) {
                                            /*---- Question was edited after answer-----*/
                                            if ($que->created_at >= $answer->created_at) {
                                                $answers[$count]['que'] = $que->que_desc;
                                                $answers[$count]['type'] = $que->que_type;
                                                $answers[$count]['field'] = $que->que_field;
                                                $answers[$count]['answer'] = $answer->answer;
                                                $count++;
                                                break;
                                            }
                                        }
                                    } else {
                                        $answers[$count]['que'] = $questionsTable->question;
                                        $answers[$count]['type'] = $questionsTable->tyep_id;
                                        $answers[$count]['field'] = $questionsTable->fieldType;
                                        $answers[$count]['answer'] = $answer->answer;
                                        $count++;
                                    }
                                } /*----------Scenario 6 - Question is edited so it will be in Questions History-------*/ else {
                                    $historyCollection = DB::table('question_histories')
                                    ->where('que_id', $question_id)
                                        ->orderBy('created_at', 'asc')
                                        ->get();
                                    foreach ($historyCollection as $que) {
                                        /*---- Question was edited after answer-----*/
                                        if ($que->created_at >= $answer->created_at) {
                                            $answers[$count]['que'] = $que->que_desc;
                                            $answers[$count]['type'] = $que->que_type;
                                            $answers[$count]['field'] = $que->que_field;
                                            $answers[$count]['answer'] = $answer->answer;
                                            $count++;
                                            break;
                                        }
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }
        if ($answers) {
            $candidate['candidateAnswers'] = $answers;
        } else {
            $candidate['candidateAnswers'] = null;
        }
    }
}
