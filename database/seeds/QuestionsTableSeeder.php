<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
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
        
        $metadata = json_decode(file_get_contents(__DIR__ . '/metadata.json'), true);

        $sectionId = 1;
        $questionId = 1;

        $dataToInsert = [];

        foreach ($questionnaire as $section => $questions) {
            foreach (array_keys($questions) as $index => $question) {
                $dataToInsert[] = [
                    'id' => $questionId,
                    'text' => $question,
                    // @todo improve type detection logic
                    'type' => ($metadata[$sectionId - 1][$index][0]),
                    'required' => (in_array('required', $metadata[$sectionId - 1][$index]) === true),
                    'section_id' => $sectionId,
                    'position' => ($index + 1),
                    // @todo add default "created_at" value
                ];
                $questionId++;
            }
            $sectionId++;
        }

        DB::table('questions')->insert($dataToInsert);

    }
}
