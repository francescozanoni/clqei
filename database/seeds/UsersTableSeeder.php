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
        DB::table('users')->insert([
            'first_name' => 'Administrator',
            'last_name' => 'Administrator',
            'email' => 'administrator@example.com',
            'password' => bcrypt('test'),
            'role' => 'administrator',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'first_name' => 'Viewer',
            'last_name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => bcrypt('test'),
            'role' => 'viewer',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
