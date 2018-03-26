<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App;
use App\Console\Commands\Interfaces\WithIntegerExitCode;
use App\Models\Student;
use App\User;
use Illuminate\Console\Command;

class GenerateUsers extends Command implements WithIntegerExitCode
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:users
                            {role : role of users to generate}
                            {number=10: number of users to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int exit code
     */
    public function handle() : int
    {

        $role = $this->argument('role');
        $number = (int)$this->argument('number');

        if (in_array($role, [User::ROLE_ADMINISTRATOR, User::ROLE_VIEWER, User::ROLE_STUDENT]) === false) {
            $this->error('Invalid user role');
            return self::INVALID_INPUT;
        }

        if ($number < 1) {
            $this->error('Invalid number of users');
            return self::INVALID_INPUT;
        }

        switch ($role) {

            case User::ROLE_ADMINISTRATOR:
            case User::ROLE_VIEWER:
                factory(User::class, $number)
                    ->states($role)
                    ->create();
                break;

            case User::ROLE_STUDENT:
                factory(Student::class, $number)
                    ->make()
                    ->each(function ($student) use ($role) {
                        factory(User::class)
                            ->states($role, $student->gender)
                            ->create()
                            ->student()
                            ->save($student);
                    });
                break;

            default:
        }

        $this->info('Users generated successfully');

        return self::SUCCESS;

    }

}
