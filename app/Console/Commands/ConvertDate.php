<?php

namespace App\Console\Commands;

use App\Models\SaleAgent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Hash;

class ConvertDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert string to date in sale_agents';

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
        $months = SaleAgent::select('*')->get();

        foreach ($months as $month) {
            $newMonth = '01-'.$month->month;
            $newMonth = Carbon::parse($newMonth)->format('Y-m-d');
            $month->update([
               'month' => $newMonth
            ]);
        }

    }
}
