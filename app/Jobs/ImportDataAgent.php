<?php

namespace App\Jobs;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Request;
use Excel;
use App\Models\GroupProduct;
use App\Models\Product;
use App\Models\User;
class ImportDataAgent
//    implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
//    protected $signature = 'crawProduct';
    protected $config;
    protected $filepath ;
    protected $month ;
    public function __construct($filepath,$month)
    {
        $this->month = $month;
        $this->filepath = $filepath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */



    public function handle()
    {
        $month = $this->month;
//        try{
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->get();

            foreach ($datas as $row) {


            }

//        } catch (\Exception $ex){
//            dd($ex->getTraceAsString().'--'.$ex->getLine());
//        }

    }
}
