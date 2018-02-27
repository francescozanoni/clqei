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
            'email' => \App\User::ROLE_ADMINISTRATOR . '@example.com',
            'password' => bcrypt(\App\User::ROLE_ADMINISTRATOR),
            'role' => \App\User::ROLE_ADMINISTRATOR,
            'created_at' => $now,
        ]);

        // Viewer user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Viewer',
            'email' => \App\User::ROLE_VIEWER . '@example.com',
            'password' => bcrypt(\App\User::ROLE_VIEWER),
            'role' => \App\User::ROLE_VIEWER,
            'created_at' => $now,
        ]);

        // Student user
        DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => 'Student',
            'email' => \App\User::ROLE_STUDENT . '@example.com',
            'password' => bcrypt(\App\User::ROLE_STUDENT),
            'role' => \App\User::ROLE_STUDENT,
            'created_at' => $now,
        ]);
    }
}
