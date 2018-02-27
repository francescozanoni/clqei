<?php
declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $now = Carbon::now()->format('Y-m-d H:i:s');

        // Administrator user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Administrator',
            'email' => 'administrator@example.com',
            'password' => bcrypt('administrator'),
            'role' => 'administrator',
            'created_at' => $now,
        ]);

        // Viewer user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('viewer'),
            'role' => 'viewer',
            'created_at' => $now,
        ]);

        // Student user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'password' => bcrypt('student'),
            'role' => 'student',
            'created_at' => $now,
        ]);
    }
}
