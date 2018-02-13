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

        $questionnaire = $this->getQuestionnaire();
        
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        
        $questionId = 1;
        $answerId = 1;

        $dataToInsert = [];

        foreach ($questionnaire as $section => $questions) {
            foreach ($questions as $question => $answers) {
                // Answers other than arrays are free text,
                // therefore not listed on "answers" table.
                if (is_array($answers) === true) {
                    foreach ($answers as $index => $answer) {
                        $dataToInsert[] = [
                            'id' => $answerId,
                            'text' => $answer,
                            'question_id' => $questionId,
                            'position' => ($index + 1),
                            'created_at' => $currentDateTime,
                        ];
                        $answerId++;
                    }
                }
                $questionId++;
            }
        }

        DB::table('answers')
            ->insert($dataToInsert);

    }
    
    private function getQuestionnaire()
    {
        return
            json_decode(
                file_get_contents(
                    database_path(
                        'seeds/questionnaire_texts_' . config('app.locale') . '.json'
                    )
                ),
                true
            );

    }
    
}
