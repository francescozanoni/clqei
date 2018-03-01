<?php
declare(strict_types = 1);

use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $questionnaire = json_decode(file_get_contents(database_path('seeds/questionnaire_texts_' . config('app.locale') . '.json')),
            true);

        foreach (array_keys($questionnaire) as $index => $section) {
            DB::table(Section::getTableName())->insert([
                'id' => ($index + 1),
                'title' => $section,
                'position' => ($index + 1),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    }
}
