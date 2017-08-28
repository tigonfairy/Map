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
use App\Models\Notification;
class ExportTD
//    implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $startMonth ;
    protected $endMonth ;
    protected $startTD ;
    protected $endTD ;
    protected $type ;
    public function __construct($startMonth,$endMonth,$startTD,$endTD,$type)
    {
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->startTD = $startTD;
        $this->endTD = $endTD;
        $this->type = $type;
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
        $startMonth = $this->startMonth;
        $endMonth = $this->endMonth;
        $startTD = $this->startTD;
        $endTD = $this->endTD;
        $type = $this->type;
        if($type == 1) {
            $type = 'quy';
        }
        if($type == 2) {
            $type = 'nua_nam';
        }
        if($type==3) {
            $type = 'ca_nam';
        }

        $exportUserArray= [];
        ob_end_clean();
        ob_start();
        $groupProduct = GroupProduct::orderBy('created_at','desc')->get();

        Excel::create('doanh_so_tien_do_'.$type.'_'.$startMonth.'_'.$endMonth, function ($excel) use ($exportUserArray,$groupProduct,$startMonth,$endMonth,$startTD,$endTD) {

            $excel->sheet('khach', function ($sheet) use ($exportUserArray,$groupProduct,$startMonth,$endMonth,$startTD,$endTD) {
                $sheet->loadView('exceltd',['groupProduct' => $groupProduct
                ,'startMonth' => $startMonth,
                    'endMonth' => $endMonth,
                    'startTD' => $startTD,
                    'endTD' => $endTD

                ]);
            });

        })->download('xlsx');
    }
}
