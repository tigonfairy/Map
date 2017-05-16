<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Hash;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Enter email (,):?');
        $password = $this->ask('Enter password (,):?');

        if ($email) {
            $this->line('Create Admin with email='.$email);
            User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'permission_id' => 1
            ]);
            $this->line('Done');
        }

    }
}
