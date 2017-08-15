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
class ImportAgent
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
    protected $name ;
    public function __construct($filepath,$name)
    {
        $this->filepath = $filepath;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */



    public function handle()
    {
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();
        $agentError = [];
            foreach ($datas as $row) {
                try{
                    $code= 0 ;
                    if(isset($row[0])) {
                        $code = $row[0];
                    }
                    if($code == 0 ) {
                        continue;
                    }
                    $name = $row[1];

                    //get lat lng :
                    if(!isset($row[2]) || empty($row[2])) {
                        continue;
                    }
                        $address = $row[2];
                    $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($row[2])."&sensor=false&region=VN";
                    $response = file_get_contents($url);
                    $response = json_decode($response, true);
                    $lat = $response['results'][0]['geometry']['location']['lat'];
                    $lng = $response['results'][0]['geometry']['location']['lng'];

                    $code_nv = trim($row[4]);
                    $code_gs = trim($row[6]);
                    $code_tv = trim($row[8]);
                    $code_gdv = trim($row[10]);
                    $manager_id = 0;
                    if($code_gdv) {
                        $gdv = User::where('code',$code_gdv)->first();
                        if($gdv) {
                            $manager_id = $gdv->id;
                        }
                    }
                    if($code_tv) {
                        $tv = User::where('code',$code_tv)->first();
                        if($tv) {
                            $manager_id = $tv->id;
                        }
                    }
                    if($code_gs) {
                        $gs = User::where('code',$code_gs)->first();
                        if($gs) {
                            $manager_id = $gs->id;
                        }
                    }

                    if($code_nv) {
                        $nv = User::where('code',$code_nv)->first();
                        if($nv) {
                            $manager_id = $nv->id;
                        }
                    }
                    if($manager_id == 0 ) {
                        $agentError[] = $code;
                        continue;
                    }
                    $agent = Agent::firstOrCreate(['code' => $code]);
                    $data = [
                      'name' => trim($name),
                        'address' => trim($address),
                        'manager_id' => $manager_id,
                        'lat' => $lat,
                        'lng' => $lng
                    ];
                    $agent->update($data);


                } catch (\Exception $ex) {
                    continue;
                }


            }

        if(count($agentError)) {
            $data['title'] = 'Đại lý chưa có quản lý khi import file '.$this->name;
            $data['content'] = [
                'agentImport' => $agentError
            ];
            $data['unread'] = 1;
            Notification::create($data);

        }
    }
}
