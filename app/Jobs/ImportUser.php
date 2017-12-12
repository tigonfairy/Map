<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Request;
use Excel;
use App\Models\GroupProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
use App\Models\Config;
class ImportUser
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
    protected $user_id ;
    public function __construct($filepath,$name,$user_id)
    {
        $this->filepath = $filepath;
        $this->name = $name;
        $this->user_id = $user_id;
    }

    public function handle()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        try{
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();

            foreach ($datas as $row) {
                if(!isset($row[0]) || empty($row[0]) ) {
                    continue;
                }
                if(!isset($row[2]) || empty($row[2]) ) {
                    continue;
                }
                $data = null;
                $code = trim($row[0]);
                $user = User::firstOrCreate(['code' => $code]);
                $data['email'] = trim($row[2]);
                if(isset($row[1]) and $row[1]) {
                    $data['name'] = $row[1];
                }
                if(isset($row[3]) and $row[3]) {
                    $data['phone'] = $row[3];
                }
                $data['fontSize'] = 12;
                $data['textColor'] = '#000000';
                $data['position'] = User::NVKD;
                if(isset($row['4']) and $row[4]) {
                    $position = str_slug($row[4]);
                    if($position == 'nvkd') {
                        $data['position'] = User::NVKD;

                    }
                    if($position == 'gs') {
                        $data['position'] = User::GSV;

                    }
                    if($position == 'tv') {
                        $data['position'] = User::TV;

                    }
                    if($position == 'pgdkd') {
                        $data['position'] = User::SALE_ADMIN;
                    }
                    if($position == 'gdv') {
                        $data['position'] = User::GĐV;
                    }
                }
                $config = Config::where('position_id',$data['position'])->first();
                if($config) {
                    $data['fontSize'] = $config->fontSize;
                    $data['textColor'] = $config->textColor;
                }
                $data['password'] =  bcrypt('123456');
                if(isset($row[6]) and $row[6]) {
                    $code_manager = trim($row[6]);
                    $manager = User::where('code',$code_manager)->first();
                    if($manager) {
                        $data['manager_id'] = $manager->id;
                    }
                }
                $user->update($data);

            }

        } catch (\Exception $ex){
            $data['title'] = 'Hệ thống lỗi chưa tồn tại khi import file '.$this->name;
            $data['content'] = [
                'error' => $ex->getTraceAsString()
            ];
            $data['user_id'] =  $this->user_id;
            $data['unread'] = 1;
            Notification::create($data);
            return;
        }

    }
}
