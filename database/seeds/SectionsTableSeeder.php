<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
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

        foreach (array_keys($questionnaire) as $index => $section) {
            DB::table('sections')->insert([
                'id' => ($index + 1),
                'text' => $section,
                'position' => ($index + 1),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    }
}
