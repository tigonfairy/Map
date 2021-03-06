<?php

namespace App\Jobs;

use App\Models\Agent;
use App\Models\CacheView;
use App\Models\Notification;
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
    protected $name ;
    protected $user_id ;
    public function __construct($filepath,$month,$name,$user_id)
    {
        $this->month = $month;
        $this->filepath = $filepath;
        $this->name = $name;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */



    public function handle()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        $month = $this->month;
        $month = '01-'.$month;
        $month = Carbon::parse($month)->format('Y-m-d');
        $agentError = [];
        try{
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->get();
            $title = $datas[0];

            foreach ($datas as $key => $row) {

                if($key  > 0) {
                    $codeAgent = trim($row[1]);
                    $agent = Agent::where('code',$codeAgent)->first();
                    if(empty($agent)) {
                        if($codeAgent) {
                            $agentError[] = $codeAgent;
                        }


                        continue;
                    }
                    $capacity = intval($row[5]);
                    $sales_plan = intval($row[6]);
                    CacheView::firstOrCreate(['agent_id' => $agent->id]);
                    foreach ($title as $k => $code ) {

                        if($k > 7) {
                            $product = Product::where('code',$code)->first();

                            if(empty($product)) {

                            } else {
                                $saleAgent = SaleAgent::firstOrCreate(['agent_id' => $agent->id,'product_id' => $product->id]);
                                $saleAgent->sales_plan = intval($sales_plan);
                                $saleAgent->capacity = intval($capacity);
                                $saleAgent->sales_real = intval($row[$k]);
                                $saleAgent->month = $month;
                                $saleAgent->code = $product->code;
                                $saleAgent->save();
                            }
                        }
                    }
                }

            }

        } catch (\Exception $ex){
            $data['title'] = 'Hệ thống lỗi chưa tồn tại khi import file '.$this->name;
            $data['content'] = [
                'error' => $ex->getTraceAsString()
            ];
            $data['user_id'] = $this->user_id;
            $data['unread'] = 1;
            Notification::create($data);
            return;
        }
        if(count($agentError)) {
            $data['title'] = 'Một số đại lý chưa tồn tại khi import file '.$this->name;
            $data['content'] = [
                'agent' => $agentError
            ];
            $data['user_id'] = $this->user_id;
            $data['unread'] = 1;
            Notification::create($data);

         }
    }
}
