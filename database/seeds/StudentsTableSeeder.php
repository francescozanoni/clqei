<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('students')->insert([
            // @todo retrieve the user ID dynamically
            'user_id' => 3,
            'identification_number' => '12345678',
            'gender' => 'male',
            'nationality' => 'Italian',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
