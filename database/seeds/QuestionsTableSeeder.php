<?php

use Carbon\Carbon;
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

        $questionnaire = json_decode(file_get_contents(database_path('seeds/' . config('app.locale') . '.json')), true);
        
        $metadata = json_decode(file_get_contents(database_path('seeds/metadata.json')), true);

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
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                $questionId++;
            }
            $sectionId++;
        }

        DB::table('questions')->insert($dataToInsert);

    }
}
