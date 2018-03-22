<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App;
use App\Console\Commands\Interfaces\WithIntegerExitCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command implements WithIntegerExitCode
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail
                            {recipient : recipient e-mail address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test e-mail';

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

        $recipient = $this->argument('recipient');

        try {

            Mail::to($recipient)
                ->send(new App\Mail\Test());

        } catch (\Throwable $e) {

            $this->error('E-mail delivery failed: ' . $e->getMessage());
            return self::UNEXPECTED_ERROR;

        }

        $this->info('E-mail successfully sent');

        return self::SUCCESS;

    }

}
