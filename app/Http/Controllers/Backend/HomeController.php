<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Area;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends AdminController
{

    public function index()
    {
       return view('admin.index');
    }
    public function dashboard(Request $request){
        $user = auth()->user();
        $month = '01-2017';
        $areaIds = $user->area()->get()->pluck('id')->toArray();
        $agents = Agent::whereIn('area_id',$areaIds)->get()->pluck('id')->toArray();
        $subAgent =
        //agent Id of user
        $agentId = $user->agent()->get()->pluck('id')->toArray();
//        $agentId= array_unique(array_merge($agentId,$agents));

//        $products = DB::table('sale_agents')
//            ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name'))
//            ->whereIn('agent_id',$agentId)->groupBy('month')->where('month','like','%2017%')
//            ->join('products','sale_agents.product_id','=','products.id')
//            ->get();
//        dd($products);
        return view('admin.dashboard', compact('month'));
    }
}
