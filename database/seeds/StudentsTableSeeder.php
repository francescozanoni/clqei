<?php
declare(strict_types = 1);

use App\Models\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table(Student::getTableName())->insert([
            'user_id' => DB::table(User::getTableName())->where('role', User::ROLE_STUDENT)->first()->id,
            'identification_number' => '12345678',
            'gender' => 'male',
            'nationality' => 'IT',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table(Student::getTableName())->insert([
            'user_id' => DB::table(User::getTableName())->where('role', User::ROLE_STUDENT)->orderBy('id', 'desc')->first()->id,
            'identification_number' => '23456789',
            'gender' => 'female',
            'nationality' => 'IT',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
