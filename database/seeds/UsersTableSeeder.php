<?php
declare(strict_types = 1);

use App\User;
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

        foreach ($this->getUserRoles() as $role) {
            DB::table(User::getTableName())->insert([
                'first_name' => 'Example',
                'last_name' => ucfirst($role),
                'email' => $role . '@' . User::EXAMPLE_DOMAIN,
                'password' => bcrypt($role),
                'role' => $role,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        
        DB::table(User::getTableName())->insert([
            'first_name' => 'Example 2',
            'last_name' => ucfirst($role),
            'email' => $role . '2@' . User::EXAMPLE_DOMAIN,
            'password' => bcrypt($role),
            'role' => $role,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

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
