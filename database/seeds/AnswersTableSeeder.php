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

        $dataToInsert = [];

        foreach ($questionnaire as $section => $questions) {
            foreach ($questions as $question => $answers) {
                // Answers other than arrays are free text
                if (is_array($answers) === true) {
                    foreach ($answers as $index => $answer) {
                        $dataToInsert[] = [
                            'id' => $answerId,
                            'text' => $answer,
                            'question_id' => $questionId,
                            'position' => ($index + 1),
                        ];
                        // @todo add default "created_at" value
                        $answerId++;
                    }
                }
                $questionId++;
            }
        }

        // If all records are inserted at once, the following error could occur:
        // [PDOException]
        // SQLSTATE[HY000]: General error: 1 too many SQL variables
        foreach (array_chunk($dataToInsert, 10) as $dataChunkToInsert) {
            DB::table('answers')->insert($dataChunkToInsert);
        }

    }
}
