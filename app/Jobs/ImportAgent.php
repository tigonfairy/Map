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
    protected $user_id ;
    public function __construct($filepath,$name,$user_id)
    {
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
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();
        $agentError = [];
        $notFound = [];
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
                    $address = str_replace(' -','-',$row[2]);
                    $address = str_replace('- ','-',$row[2]);
                    $address = str_replace('-',',',$row[2]);

                    $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false&region=VN";

                    $response = file_get_contents($url);
                    $response = json_decode($response, true);
                    if(!isset($response['results'][0])) {
                        $notFound[] = $code;
                        continue;

                    }
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


                    $attribute = 0;
                    $config = [];
                    if (file_exists(public_path().'/config/config.json')) {
                        $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
                    }
                    $icon = '';
                    if(isset($row[11]) and $row[11]) {
                        $str = str_slug($row[11]);
                        if($str == 'dl-moi') {
                            $attribute = Agent::agentNew;
                        }
                        if($str == 'dl-doi-thu') {
                            $attribute = Agent::agentRival;
                        }
                    }
                    $rank = 0;

                    if(isset($row[12]) and $row[12]) {


                        $str = str_slug($row[12]);
                        if($str == 'kim-cuong') {
                            $rank = Agent::diamond;
                            $icon = (isset($config['agent_diamond'])) ? $config['agent_diamond'] : null;
                        }
                        if($str == 'vang') {
                            $rank = Agent::gold;
                            $icon = (isset($config['agent_gold'])) ? $config['agent_gold'] : null;
                        }
                        if($str == 'bac') {
                            $rank = Agent::silver;
                            $icon = (isset($config['agent_silver'])) ? $config['agent_silver'] : null;
                        }
                        if($str == 'chua-xep-hang') {
                            $rank = Agent::unclassified;
                            $icon = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
                        }
                    }
                    if($attribute == Agent::agentRival) {
                        $icon = (isset($config['agent_rival'])) ? $config['agent_rival'] : null;
                    }

                    if($attribute == Agent::agentNew) {
                        $icon =  (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
                    }


                    $agent = Agent::firstOrCreate(['code' => $code]);
                    $data = [
                      'name' => trim($name),
                        'address' => trim($address),
                        'manager_id' => $manager_id,
                        'lat' => $lat,
                        'lng' => $lng,
                        'attribute' => $attribute,
                        'rank' => $rank,
                        'icon' => $icon
                    ];


                    //gan cap bac cho agent

                    $user = User::find($manager_id);
                    if($user->position == User::SALE_ADMIN) {
                        $data['pgdkd'] = $manager_id;
                    }

                    if($user->position == User::GĐV) {
                        $data['gdv'] = $manager_id;
                    }

                    if($user->position == User::TV) {
                        $data['tv'] = $manager_id;
                        $user2 = $user->manager;
                        if($user2 and $user2->position == User::GĐV) {
                            $data['gdv'] = $user2->id;
                            if($user2->manager->position == User::SALE_ADMIN) {
                                $data['pgdkd'] = $user2->manager->id;
                            }
                        }
                        if($user2 and $user2->position == User::SALE_ADMIN) {
                            $data['pgdkd'] = $user2->id;
                        }
                    }
                    if($user->position == User::GSV) {
                        $data['gsv'] = $manager_id;
                        $user2 = $user->manager;
                        if($user2 and $user2->position == User::SALE_ADMIN) {
                            $data['pgdkd'] = $user2->id;
                        }
                        if($user2 and $user2->position == User::GĐV) {
                            $data['gdv'] = $user2->id;
                            if($user2->manager->position == User::SALE_ADMIN) {
                                $data['pgdkd'] = $user2->manager->id;
                            }
                        }
                        if($user2 and $user2->position == User::TV) {
                            $data['tv'] = $user2->id;

                            $user2 = $user2->manager;
                            if($user2 and $user2->position == User::GĐV) {
                                $data['gdv'] = $user2->id;
                                if($user2->manager->position == User::SALE_ADMIN) {
                                    $data['pgdkd'] = $user2->manager->id;
                                }
                            }
                            if($user2 and $user2->position == User::SALE_ADMIN) {
                                $data['pgdkd'] = $user2->id;
                            }
                        }
                    }

                    if($user->position == User::NVKD) {
                        $user2 = $user->manager;

                        if($user2 and $user2->position == User::GSV) {
                            $data['gsv'] = $user2->id;
                            $user2 = $user2->manager;
                            if($user2 and $user2->position == User::SALE_ADMIN) {
                                $data['pgdkd'] = $user2->id;
                            }
                            if($user2 and $user2->position == User::GĐV) {
                                $data['gdv'] = $user2->id;
                                if($user2->manager->position == User::SALE_ADMIN) {
                                    $data['pgdkd'] = $user2->manager->id;
                                }
                            }
                            if($user2 and $user2->position == User::TV) {
                                $data['tv'] = $user2->id;

                                $user2 = $user2->manager;
                                if($user2 and $user2->position == User::GĐV) {
                                    $data['gdv'] = $user2->id;
                                    if($user2->manager->position == User::SALE_ADMIN) {
                                        $data['pgdkd'] = $user2->manager->id;
                                    }
                                }
                                if($user2 and $user2->position == User::SALE_ADMIN) {
                                    $data['pgdkd'] = $user2->id;
                                }
                            }
                        }

                        if($user2 and $user2->position == User::TV) {
                            $data['tv'] = $user2->id;
                            $user2 = $user2->manager;
                            if($user2 and $user2->position == User::GĐV) {
                                $data['gdv'] = $user2->id;
                                if($user2->manager->position == User::SALE_ADMIN) {
                                    $data['pgdkd'] = $user2->manager->id;
                                }
                            }
                            if($user2 and $user2->position == User::SALE_ADMIN) {
                                $data['pgdkd'] = $user2->id;
                            }
                        }
                        if($user2 and  $user2->position ==  User::GĐV) {
                            $data['gdv'] = $user2->id;
                        }
                    }



                    //


                    $agent->update($data);


                } catch (\Exception $ex) {
                    continue;
                }


            }

        if(count($agentError)) {
            $data['title'] = 'Lỗi khi import file  đại lý vào hệ thống'.$this->name;
            $data['content'] = [
                'agentImport' => $agentError,
                'notFound' => $notFound
            ];
            $data['user_id'] = $this->user_id ;
            $data['unread'] = 1;
            Notification::create($data);

        }
    }
}
