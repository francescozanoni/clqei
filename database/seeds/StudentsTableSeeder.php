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
            'user_id' => DB::table('users')->where('role', 'student')->first()->id,
            'identification_number' => '12345678',
            'gender' => 'male',
            'nationality' => 'Italian',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
