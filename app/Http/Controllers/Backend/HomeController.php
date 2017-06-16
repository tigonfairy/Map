<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Area;
use Carbon\Carbon;
use DB;
use Response;
use Illuminate\Http\Request;

class HomeController extends AdminController
{

    public function index()
    {
       return view('admin.index');
    }
    public function dashboard(Request $request){
        $user = auth()->user();
        $year = Carbon::now()->year;
        if($user->email == 'admin@gmail.com'){
            $area = Area::select('*')->get()->pluck('id')->toArray();
            $agentId = Agent::whereIn('area_id',$area)->get()->pluck('id')->toArray();
        }else{
            $area = $user->area()->get()->pluck('id')->toArray();
            $subArea = Area::whereIn('parent_id',$area)->get()->pluck('id')->toArray();
            $areaIds = array_unique(array_merge($area,$subArea));
            $agents = Agent::whereIn('area_id',$areaIds)->get()->pluck('id')->toArray();
            //agent Id of user
            $agentId = $user->agent()->get()->pluck('id')->toArray();
            $agentId= array_unique(array_merge($agentId,$agents));
        }

        //chart cot

        $products = DB::table('sale_agents')
            ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,month'))
            ->whereIn('agent_id',$agentId)->groupBy('month')->where('month','like','%'.$year.'%')->orderBy('month')
            ->get()->toArray();
        $sales_plan = [];
        $sales_real = [];


        for($i = 0;$i < 12;$i++){
            $sales_plan[$i] = 0;
            $sales_real[$i] = 0;
        }

        foreach ($products as $key => $product){
            $sales_plan[$key] = intval($product->sales_plan);
            $sales_real[$key] = intval($product->sales_real);

        }

        //end chart cot


        return view('admin.dashboard', compact('month','sales_plan','sales_plan','sales_real'));
    }

    public function chartDashboard(Request $request){
        $type = $request->input('type');
        // thang gan nhat
        $user = auth()->user();
        $year = Carbon::now()->year;
        if($user->email == 'admin@gmail.com'){
            $area = Area::select('*')->get()->pluck('id')->toArray();
            $agentId = Agent::whereIn('area_id',$area)->get()->pluck('id')->toArray();
        }else{
            $area = $user->area()->get()->pluck('id')->toArray();
            $subArea = Area::whereIn('parent_id',$area)->get()->pluck('id')->toArray();
            $areaIds = array_unique(array_merge($area,$subArea));
            $agents = Agent::whereIn('area_id',$areaIds)->get()->pluck('id')->toArray();
            //agent Id of user
            $agentId = $user->agent()->get()->pluck('id')->toArray();
            $agentId= array_unique(array_merge($agentId,$agents));
        }
        if($type == 1){ // thang gần nhất
            $month = Carbon::now()->format('m-Y');
            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name,month'))
                ->whereIn('agent_id',$agentId)->where('month','like',$month)->orderBy('month')->groupBy('product_id')
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();

            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->name,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tháng '.$month ],200);
        } elseif($type == 2){ // tháng có doanh số cao nhất

            $monthHighest = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                ->whereIn('agent_id',$agentId)->where('month','like','%'.$year.'%')->groupBy('month')->orderBy('sales_real','desc')
                ->first()->month;

            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name,month'))
                ->whereIn('agent_id',$agentId)->where('month','like',$monthHighest)->groupBy('product_id')->orderBy('sales_real','desc')
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();
            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->name,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tháng doanh số cao nhất '.$monthHighest ],200);
        }elseif($type == 3){ // trung bình tháng
            $month = Carbon::now()->format('m-Y');

            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name'))
                ->whereIn('agent_id',$agentId)->groupBy('product_id')->where('month','like','%'.$year.'%')->where('month','<',$month)
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();
            $countMonth = DB::table('sale_agents')->select('month')->whereIn('agent_id',$agentId)
                ->groupBy('month')->where('month','like','%'.$year.'%')->where('month','<',$month)->get()->count();
            $chartData = [];
            foreach ($products as $key => $p){
                $chartData[] = ['name' => $p->name,'y' => round(intval($p->sales_real)/$countMonth,2)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'trung bình '.$countMonth.' tháng' ],200);
        }else { // tong san luong
            $month = Carbon::now()->format('m-Y');
            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name'))
                ->whereIn('agent_id',$agentId)->where('month','like','%'.$year.'%')->where('month','<=',$month)->groupBy('product_id')
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();


            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->name,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tổng sản lượng đến tháng '.$month ],200);
        }


    }
}
