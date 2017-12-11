<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SaleAgent;
use App\Models\User;
use Illuminate\Console\Command;
use Hash;

class UpdateCacheView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-cache-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

    }
}
