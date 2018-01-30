<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Administrator user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Administrator',
            'email' => 'administrator@example.com',
            'password' => bcrypt('administrator'),
            'role' => 'administrator',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // Viewer user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('viewer'),
            'role' => 'viewer',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // Student user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => 'student@example.com',
            'password' => bcrypt('student'),
            'role' => 'student',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
