<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // @todo make the chosen file dynamic, based on configuration
        $questionnaire = json_decode(file_get_contents(__DIR__ . '/it.json'), true);

        $questionId = 1;
        $answerId = 1;

        foreach ($questionnaire as $section => $questions) {
            foreach ($questions as $question => $answers) {
                // Answers other than arrays are free text
                if (is_array($answers) === true) {
                    foreach ($answers as $index => $answer) {
                        DB::table('answers')->insert([
                            'id' => $answerId,
                            'text' => $answer,
                            'question_id' => $questionId,
                            'position' => ($index + 1),
                        ]);
                        $answerId++;
                    }
                }
                $questionId++;
            }
        }

    }
}
