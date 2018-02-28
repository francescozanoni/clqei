<?php
declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        foreach ($this->getUserRoles() as $role) {
            DB::table('users')->insert([
                'first_name' => 'Example',
                'last_name' => ucfirst($role),
                'email' => $role . '@example.com',
                'password' => bcrypt($role),
                'role' => $role,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

    }

    /**
     * @return Generator
     */
    private function getUserRoles()
    {
        yield User::ROLE_ADMINISTRATOR;
        yield User::ROLE_VIEWER;
        yield User::ROLE_STUDENT;
    }
}
