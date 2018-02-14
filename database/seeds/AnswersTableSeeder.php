<?php

use Carbon\Carbon;
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

        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        $dataToInsert = [];

        foreach ($this->getQuestionAnswers() as $questionId => $answers) {
            // Answers other than arrays are free text,
            // therefore not listed on "answers" table.
            if (is_array($answers) === true) {
                foreach ($answers as $answerIndex => $answer) {
                    $dataToInsert[] = [
                        'text' => $answer,
                        'question_id' => $questionId,
                        'position' => ($answerIndex + 1),
                        'created_at' => $currentDateTime,
                    ];
                }
            }
        }

        DB::table('answers')
            ->insert($dataToInsert);

    }

    /**
     * Generator of question IDs and the related answers
     *
     * @return Generator
     */
    private function getQuestionAnswers()
    {
        $questionnaire =
            json_decode(
                file_get_contents(
                    database_path(
                        'seeds/questionnaire_texts_' . config('app.locale') . '.json'
                    )
                ),
                true
            );

        $questionId = 1;

        foreach ($questionnaire as $section => $sectionQuestions) {
            foreach ($sectionQuestions as $question => $answers) {
                yield $questionId++ => $answers;
            }
        }

    }

}
