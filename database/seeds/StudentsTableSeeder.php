<?php
declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('students')->insert([
            'user_id' => DB::table('users')->where('role', User::ROLE_STUDENT)->first()->id,
            'identification_number' => '12345678',
            'gender' => 'male',
            'nationality' => 'IT',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
