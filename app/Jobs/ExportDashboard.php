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
use Illuminate\Support\Facades\Storage;

class ExportDashboard
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
    protected $type ;
    protected $user ;
    public function __construct($startMonth,$endMonth,$type,$user)
    {
        $this->startMonth = $startMonth;
        $this->endMonth = $endMonth;
        $this->type = $type;
        $this->user = $user;
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
        $type = $this->type;
        $user = $this->user;
        $time = time().rand(1,99999);

        ob_end_clean();
        ob_start();
        $groupProduct = GroupProduct::orderBy('created_at','desc')->get();

        $file = Excel::create('doanh_so_san_pham_'.$startMonth.'_'.$endMonth.'_'.$time, function ($excel) use ($groupProduct,$startMonth,$endMonth,$user,$type) {

            $excel->sheet('khach', function ($sheet) use ($groupProduct,$startMonth,$endMonth,$user,$type) {
                $sheet->loadView('exportDashboard',['groupProduct' => $groupProduct
                ,'startMonth' => $startMonth,
                    'endMonth' => $endMonth,
                    'type' => $type,
                    'user' => $user

                ]);
            });

        })->store('xls', false, true);

//        $fileGet = Storage::get($file['full']);

        $data['title'] = 'Link tải file export ở dashboard';
        $data['content'] = [
            'link' => $file['full']
        ];
        $data['unread'] = 1;
        Notification::create($data);
    }
}
