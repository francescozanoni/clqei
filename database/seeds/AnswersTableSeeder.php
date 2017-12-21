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

        // @todo move to an external file, maybe JSON

        DB::table('answers')->insert(['text' => 'Maschio', 'code' => 'A', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => 'Femmina', 'code' => 'B', 'question_id' => 0]);

        // No answers for age

        DB::table('answers')->insert(['text' => 'Italiana', 'code' => 'A', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => 'Altro', 'code' => 'B', 'question_id' => 0]);

        DB::table('answers')->insert(['text' => '1', 'code' => 'A', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '2', 'code' => 'B', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '3', 'code' => 'C', 'question_id' => 0]);

        DB::table('answers')->insert(['text' => 'Regolare', 'code' => 'A', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => 'Fuori corso', 'code' => 'B', 'question_id' => 0]);

        DB::table('answers')->insert(['text' => '1', 'code' => 'A', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '2', 'code' => 'B', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '3', 'code' => 'C', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '4', 'code' => 'D', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '5', 'code' => 'E', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '6', 'code' => 'F', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '7', 'code' => 'G', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '8', 'code' => 'H', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '9', 'code' => 'I', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '10', 'code' => 'J', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '11', 'code' => 'K', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '12', 'code' => 'L', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '13', 'code' => 'M', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '14', 'code' => 'N', 'question_id' => 0]);
        DB::table('answers')->insert(['text' => '15', 'code' => 'O', 'question_id' => 0]);

        DB::table('answers')->insert([
            'text' => 'Diurno (esempio: mattina e/o pomeriggio)',
            'code' => 'A',
            'question_id' => 0
        ]);
        DB::table('answers')->insert(['text' => 'In turno (anche di notte)', 'code' => 'B', 'question_id' => 0]);

        // Average number of patients during a shift
        for ($i = 1; $i <= 100; $i++) {
            DB::table('answers')->insert(['text' => $i, 'code' => 'A', 'question_id' => 0]);
        }

        DB::table('answers')->insert([
            'text' => 'Sì (vai alla domanda alla domanda 25)',
            'code' => 'A',
            'question_id' => 0
        ]);
        DB::table('answers')->insert([
            'text' => 'No, ero l\'unico/a studente (vai alla domanda 27)',
            'code' => 'B',
            'question_id' => 0
        ]);

        // question 25
        DB::table('answers')->insert([
            'text' => 'C\'erano studenti di infermieristica del mio stesso anno di corso',
            'code' => 'A',
            'question_id' => 0
        ]);
        DB::table('answers')->insert([
            'text' => 'C\'erano studenti di infermieristica di altri anni di corso',
            'code' => 'B',
            'question_id' => 0
        ]);
        DB::table('answers')->insert([
            'text' => 'C\'erano studenti di altre professioni sanitarie',
            'code' => 'C',
            'question_id' => 0
        ]);
        DB::table('answers')->insert([
            'text' => 'C\'erano studenti di medicina/specializzandi',
            'code' => 'D',
            'question_id' => 0
        ]);

        // Sections 1, 2, 3, 4 and 5
        for ($i = 1; $i <= 22; $i++) {
            DB::table('answers')->insert(['text' => 'Per nulla', 'code' => 'A', 'question_id' => $i]);
            DB::table('answers')->insert(['text' => 'Abbastanza', 'code' => 'B', 'question_id' => $i]);
            DB::table('answers')->insert(['text' => 'Molto', 'code' => 'C', 'question_id' => $i]);
            DB::table('answers')->insert(['text' => 'Moltissimo', 'code' => 'D', 'question_id' => $i]);
        }

    }
}
