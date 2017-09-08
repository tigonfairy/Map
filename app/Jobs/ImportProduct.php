<?php

namespace App\Jobs;

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
use App\Models\Notification;
class ImportProduct
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
        try{
            $datas = Excel::selectSheetsByIndex(0)->load($this->filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();

            foreach ($datas as $row) {

                if(!isset($row[4]) || empty($row[4])) {
                    continue;
                }

                $groupName = trim($row[4]);
                if(isset($row[1]) and empty($row[1]) and isset($row[2]) and empty($row[2]) and isset($row[3]) and empty($row[3])) {
                    break;
                }
                $groupProduct = GroupProduct::where('name_vn','like','%'.$groupName.'%')->first();
                if(empty($groupProduct)) {
                    $groupProduct = GroupProduct::firstOrCreate(['name_vn' => $groupName]);
                }

                $name = (isset($row[0]) and !empty($row[0])) ? $row[0] : 'Chưa có tên';
                $cbd = null;
                $maxgreen = null;
                $maxgro = null;

                if(isset($row[1]) and $row[1]) {
                    if(is_numeric($row[1])) {
                        $row[1] = intval($row[1]);
                      }

                    $cbd = Product::firstOrCreate(
                        ['level' => 1, 'code' => $row[1], 'name_code' => 'cbd'
                        ]);
                    $cbd->update([
                        'name_vn' => $name,
                        'parent_id' => $groupProduct->id,
                    ]);
                }
                if(isset($row[2]) and $row[2]) {
                    if(is_numeric($row[2])) {
                        $row[2] = intval($row[2]);
                    }
                    $maxgreen = Product::firstOrCreate(
                        ['level' => 1,
                            'code' => $row[2],
                            'name_code' => 'maxgreen'
                        ]);
                    $maxgreen->update([
                        'name_vn' => $name,
                        'parent_id' => $groupProduct->id,
                    ]);
                }
                if(isset($row[3]) and $row[3]) {
                    if(is_numeric($row[3])) {
                        $row[3] = intval($row[3]);
                    }
                    $maxgro = Product::firstOrCreate(
                        [   'level' => 1,
                            'code' => $row[3],
                            'name_code' => 'maxgro'
                        ]);
                    $maxgro->update([
                        'name_vn' => $name,
                        'parent_id' => $groupProduct->id,
                    ]);
                }
                //check da ton tai hay chua
                $product_id = 0;
                if($cbd) {
                    if($cbd->product_id) {
                        $product_id = $cbd->product_id;
                    }
                }
                if($maxgreen) {
                    if($maxgreen->product_id) {
                        $product_id = $maxgreen->product_id;
                    }
                }
                if($maxgro) {
                    if($maxgro->product_id) {
                        $product_id = $maxgro->product_id;
                    }
                }

                if($product_id = 0) {
                    $product = Product::firstOrCreate(['name_vn' => $name,'level'=> 0]);
                    $product->level = 0;
                    $product->product_id = 0;
                    $product->parent_id =$groupProduct->id;
                    $product->save();
                } else {
                    $product = Product::find($product_id);
                    if(empty($product)) {
                        $product = Product::firstOrCreate(['name_vn' => $name,'level'=> 0]);
                    }
                    $product->name_vn = $name;
                    $product->level = 0;
                    $product->product_id = 0;
                    $product->parent_id =$groupProduct->id;
                    $product->save();
                }
                if($cbd) {
                    $cbd->product_id = $product->id;
                    $cbd->save();
                }
                if($maxgreen) {
                    $maxgreen->product_id = $product->id;
                    $maxgreen->save();
                }
                if($maxgro) {
                    $maxgro->product_id = $product->id;
                    $maxgro->save();
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

    }
}
