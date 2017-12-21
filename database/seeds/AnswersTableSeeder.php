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
        DB::table('answers')->insert([
            'text' => '<ANSWER 1.A TEXT>',
            'code' => 'A',
            'question_id' => 1
        ]);
        DB::table('answers')->insert([
            'text' => '<ANSWER 1.B TEXT>',
            'code' => 'B',
            'question_id' => 1
        ]);
        DB::table('answers')->insert([
            'text' => '<ANSWER 1.C TEXT>',
            'code' => 'C',
            'question_id' => 1
        ]);
        DB::table('answers')->insert([
            'text' => '<ANSWER 1.D TEXT>',
            'code' => 'D',
            'question_id' => 1
        ]);
    }
}
