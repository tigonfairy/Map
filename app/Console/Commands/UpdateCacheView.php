<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\CacheView;
use App\Models\Product;
use App\Models\SaleAgent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Hash;
use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Support\Facades\Log;

class UpdateCacheView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-cache-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//       Log::info('hay qua');
//       $this->line('vai te');
        try{
            $idChange= [];
            $now = Carbon::now();
            $cacheViews = CacheView::select('*')->get();
            foreach ($cacheViews as $cache) {
                $agent = Agent::find($cache->agent_id);
                if($agent) {
                    $idChange[] = $agent->manager_id;
                    if($agent->gdv != 0) {
                        $idChange[] = $agent->gdv;
                    }
                    if($agent->tv != 0) {
                        $idChange[] = $agent->tv;
                    }
                    if($agent->gsv != 0) {
                        $idChange[] = $agent->gsv;
                    }
                }

            }
            $idChange = array_unique($idChange);

            foreach ($idChange as $id) {
                //dasboard
                $user = User::find($id);

                if($user) {
                    $year = Carbon::now()->year;
                    $agentId = null;
                    if($user->position == User::NVKD) {
                        $agentId = Agent::where('manager_id',$id)->get()->pluck('id')->toArray();
                    }
                    if($user->position == User::GSV) {
                        $agentId = Agent::where('manager_id',$id)->orWhere('gsv',$id)->get()->pluck('id')->toArray();
                    }
                    if($user->position == User::TV) {
                        $agentId = Agent::where('manager_id',$id)->orWhere('tv',$id)->get()->pluck('id')->toArray();
                    }
                    if($user->position == User::GĐV) {
                        $agentId = Agent::where('manager_id',$id)->orWhere('gdv',$id)->get()->pluck('id')->toArray();
                    }
                    if($agentId) {
                        //dashboard
                        $products = DB::table('sale_agents')
                            ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                            ->whereIn('agent_id', $agentId)->groupBy('month')->where('month', '>=',$year.'-01-01')->where('month', '<=',$year.'-12-01' )->orderBy('month')
                            ->get()->toArray();
                        Cache::forever('total-sale-real-' . $id, $products);


                        //thang gan nhat
                        $lastMonth = DB::table('sale_agents')
                            ->select(\DB::raw('month'))->orderBy('month', 'desc')
                            ->first()->month;
                        $products = DB::table('sale_agents')
                            ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code,month'))
                            ->whereIn('agent_id', $agentId)->where('month', $lastMonth)->orderBy('month')->groupBy('sale_agents.product_id')
                            ->get()->toArray();
                        Cache::forever('lastest-month-' . $user->id, $products);

                        //doanh so cao nhat
                        $monthHighest = DB::table('sale_agents')
                            ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                            ->whereIn('agent_id', $agentId)->where('month', '>=', $year.'-01-01')->where('month', '<=', $year.'-12-01')->groupBy('month')->orderBy('sales_real', 'desc')
                            ->first()->month;
                        $products = DB::table('sale_agents')
                            ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code,month'))
                            ->whereIn('agent_id', $agentId)->where('month', $monthHighest)->groupBy('sale_agents.product_id')->orderBy('sales_real', 'desc')
                            ->get()->toArray();
                        Cache::forever('biggest-sales-month-' . $user->id, $products);

                        //trung binh thang
                        $month = Carbon::now()->format('m-Y');
                        $products = DB::table('sale_agents')
                            ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code'))
                            ->whereIn('agent_id', $agentId)->groupBy('product_id')->where('month', '>=', $year.'-01-01')->where('month', '<=', $month)
                            ->get()->toArray();
                        Cache::forever('average-month-' . $user->id, $products);
                        Cache::forever('total-sales-month-' . $user->id, $products);
                    }

                }


            }

            User::whereIn('position',[User::ADMIN,User::SALE_ADMIN])->chunk(100,function($users) {
                foreach ($users as $user) {
                    //dashboard
                    $year = Carbon::now()->year;
                    $id = $user->id;
                    $products = DB::table('sale_agents')
                        ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                        ->groupBy('month')->where('month', '>=', $year.'-01-01')->where('month', '<=', $year.'-12-01')->orderBy('month')
                        ->get()->toArray();
                    Cache::forever('total-sale-real-' . $id, $products);


                    //thang gan nhat
                    $lastMonth = DB::table('sale_agents')
                        ->select(\DB::raw('month'))->orderBy('month', 'desc')
                        ->first()->month;
                    $products = DB::table('sale_agents')
                        ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code,month'))
                        ->where('month', $lastMonth)->orderBy('month')->groupBy('sale_agents.product_id')
                        ->get()->toArray();
                    Cache::forever('lastest-month-' . $user->id, $products);

                    //doanh so cao nhat
                    $monthHighest = DB::table('sale_agents')
                        ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                        ->where('month', '>=', $year.'-01-01')->where('month', '<=', $year.'-12-01')->groupBy('month')->orderBy('sales_real', 'desc')
                        ->first()->month;
                    $products = DB::table('sale_agents')
                        ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code,month'))
                        ->where('month', $monthHighest)->groupBy('sale_agents.product_id')->orderBy('sales_real', 'desc')
                        ->get()->toArray();
                    Cache::forever('biggest-sales-month-' . $user->id, $products);

                    //trung binh thang
                    $month = Carbon::now()->format('m-Y');
                    $products = DB::table('sale_agents')
                        ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,code'))
                        ->groupBy('product_id')->where('month', '>=', $year.'-01-01')->where('month', '<=', $month)
                        ->get()->toArray();
                    Cache::forever('average-month-' . $user->id, $products);
                    Cache::forever('total-sales-month-' . $user->id, $products);
                }
            });

            $this->line('Succeesss');
            CacheView::where('created_at','<',$now)->delete();
        } catch(\Exception $ex) {
            $this->line($ex->getMessage().'!'.$ex->getLine());
        }


    }
}
