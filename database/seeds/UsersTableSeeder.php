<?php

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
            'last_name' => '',
            'email' => 'administrator@example.com',
            'password' => bcrypt('admin'),
            'role' => 'administrator',
        ]);
        // @todo add default "created_at" value
    }
}
