<?php

use App\Domain\Hiring\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionType = QuestionType::create([
            'type' => 'text',
        ]);

        $questionType = QuestionType::create([
            'type' => 'file',
        ]);
    }
}
