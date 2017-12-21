<?php

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

        // @todo move to an external file, maybe JSON

        DB::table('sections')->insert([
            'id' => 1,
            'text' => 'Qualità delle strategie tutoriali',
        ]);
        DB::table('sections')->insert([
            'id' => 2,
            'text' => 'Opportunità di apprendimento',
        ]);
        DB::table('sections')->insert([
            'id' => 3,
            'text' => 'Sicurezza e qualità dell\'assistenza',
        ]);
        DB::table('sections')->insert([
            'id' => 4,
            'text' => 'Auto-apprendimento'
        ]);
        DB::table('sections')->insert([
            'id' => 5,
            'text' => 'Qualità dell\'ambiente di apprendimento',
        ]);
    }
}
