<?php
declare(strict_types = 1);

use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $questionnaire = json_decode(file_get_contents(database_path("seeds/questionnaire_texts_" . config("app.locale") . ".json")), true);

        $metadata = json_decode(file_get_contents(database_path("seeds/questionnaire_metadata.json")), true);

        $sectionId = 1;
        $questionId = 1;

        $dataToInsert = [];

        foreach ($questionnaire as /* $section => */ $questions) {
            foreach (array_keys($questions) as $index => $question) {
                $dataToInsert[] = [
                    "id" => $questionId,
                    "text" => $question,
                    "type" => ($metadata[$sectionId - 1][$index]["type"]),
                    "required" => (in_array("required", $metadata[$sectionId - 1][$index]) === true &&
                                   $metadata[$sectionId - 1][$index]["required"] == true),
                    "options" => (isset($metadata[$sectionId - 1][$index]["options"]) === true ? json_encode($metadata[$sectionId - 1][$index]["options"]) : null),
                    "section_id" => $sectionId,
                    "position" => ($index + 1),
                    "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                ];
                $questionId++;
            }
            $sectionId++;
        }

        DB::table(Question::getTableName())
            ->insert($dataToInsert);

    }
}
