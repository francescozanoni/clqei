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
        $userDomain = 'example.com';

        foreach ($this->getUserRoles() as $role) {
            DB::table('users')->insert([
            'first_name' => 'Example',
            'last_name' => ucfirst($role),
            'email' => $role . '@' . $userDomain,
            'password' => bcrypt($role),
            'role' => $role,
            'created_at' => $now,
        ]);
        }
      
    }
  
    private function getUserRoles()
    {
      yield \App\User::ROLE_ADMINISTRATOR;
      yield \App\User::ROLE_VIEWER;
      yield \App\User::ROLE_STUDENT;
    }
}
