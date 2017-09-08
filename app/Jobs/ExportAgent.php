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
class ExportAgent
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
    protected $user_id ;
    public function __construct($startMonth,$endMonth,$user_id)
    {
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
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
        $startMonth = $this->startMonth;
        $endMonth = $this->endMonth;

        $exportUserArray= [];
        ob_end_clean();
        ob_start();
        $groupProduct = GroupProduct::orderBy('created_at','desc')->get();

        Excel::create('doanh_so_'.$startMonth.'_'.$endMonth, function ($excel) use ($exportUserArray,$groupProduct,$startMonth,$endMonth) {

            $excel->sheet('khach', function ($sheet) use ($exportUserArray,$groupProduct,$startMonth,$endMonth) {
                $sheet->loadView('exportExcel',['groupProduct' => $groupProduct
                ,'startMonth' => $startMonth,
                    'endMonth' => $endMonth

                ]);
            });

        })->download('xlsx');
    }
}
