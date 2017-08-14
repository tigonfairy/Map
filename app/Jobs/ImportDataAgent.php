<?php

namespace App\Jobs;

use App\Models\Agent;
use App\Models\SaleAgent;
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
            $title = $datas[0];

            foreach ($datas as $key => $row) {

                if($key  > 0) {
                    $codeAgent = trim($row[1]);
                    $agent = Agent::where('code',$codeAgent)->first();
                    if(empty($agent)) {
                        continue;
                    }
                    $capacity = intval($row[5]);
                    $sales_plan = intval($row[6]);
                    foreach ($title as $k => $code ) {

                        if($k > 7) {
                            $product = Product::where('code',$code)->first();

                            if(empty($product)) {

                            } else {
                                $saleAgent = SaleAgent::firstOrCreate(['agent_id' => $agent->id,'product_id' => $product->id]);
                                $saleAgent->sales_plan = $sales_plan;
                                $saleAgent->capacity = $capacity;
                                $saleAgent->sales_real = intval($row[$k]);
                                $saleAgent->month = $month;
                                $saleAgent->save();
                            }
                        }
                    }
                }

            }

//        } catch (\Exception $ex){
//            dd($ex->getTraceAsString().'--'.$ex->getLine());
//        }

    }
}