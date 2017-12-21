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
        DB::table('questions')->insert(['text' => '<QUESTION 1 TEXT>']);
        DB::table('questions')->insert(['text' => '<QUESTION 2 TEXT>']);
        DB::table('questions')->insert(['text' => '<QUESTION 3 TEXT>']);
    }
}
