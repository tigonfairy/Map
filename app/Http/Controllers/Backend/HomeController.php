<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\ExportDashboard;
use App\Models\Agent;
use App\Models\Area;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Http\Request;
use Validator;
use App\Models\SaleAgent;
class HomeController extends AdminController
{

    public function index()
    {
       return view('admin.index');
    }

    public function dashboard(Request $request)
    {

        $user = auth()->user();

        $year = Carbon::now()->year;
        $locations = [];
        $month = Carbon::now()->format('m-Y');
        if( $user->position == User::ADMIN || $user->position == User::SALE_ADMIN){

            $userIds =  User::pluck('id')->toArray();
          //  $userIds = $users->pluck('id')->toArray();
            $agentId = Agent::whereIn('manager_id', $userIds)->pluck('id')->toArray();
//            $agentId = $agents->pluck('id')->toArray();

        } else {
            $area = $user->area()->get()->pluck('id')->toArray();
            $subArea = Area::whereIn('parent_id', $area)->get()->pluck('id')->toArray();
            $areaIds = array_unique(array_merge($area, $subArea));
            $agentIds = Agent::whereIn('area_id', $areaIds)->get()->pluck('id')->toArray();

            //agent Id of user
            $agentId = $user->agent()->get()->pluck('id')->toArray();
            $agentId = array_unique(array_merge($agentId, $agentIds));
        }

        //chart cot
        $products = DB::table('sale_agents')
            ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
            ->whereIn('agent_id', $agentId)->where('month', 'like', '%' . $year . '%')
            ->orderBy('month')->groupBy('month')->get()->toArray();
        $sales_plan = [];
        $sales_real = [];


        for ($i = 0; $i < 12; $i++) {
            $sales_real[$i] = 0;
        }

        foreach ($products as $key => $product) {
            $i = intval(explode('-',$product->month)[0] - 1);
            $sales_real[$i] = intval($product->sales_real);
        }

        //end chart cot

        return view('admin.dashboard', compact('month', 'sales_plan', 'sales_real', 'user'));
    }

    public function chartDashboard(Request $request){
        $type = $request->input('type');
        // thang gan nhat
        $user = auth()->user();
        $year = Carbon::now()->year;
        if( $user->position == User::ADMIN || $user->position == User::SALE_ADMIN){
            $area = Area::pluck('id')->toArray();
            $agentId = Agent::pluck('id')->toArray();

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

//            $month = Carbon::now()->format('m-Y');
            $lastMonth = DB::table('sale_agents')
                ->select(\DB::raw('month'))
                ->whereIn('agent_id',$agentId)->where('month','like','%'.$year.'%')->groupBy('month')
                ->orderBy('month','desc')->first()->month;

            $products = DB::table('sale_agents')
                ->join('products','sale_agents.product_id','=','products.id')
                ->whereIn('agent_id',$agentId)->where('month','like',$lastMonth)->orderBy('month')
                ->select(\DB::raw('SUM(sales_real) as sales_real, sale_agents.product_id, products.code, month'))
                ->groupBy('sale_agents.product_id')
                ->get()->toArray();

            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->code,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tháng '.$lastMonth ],200);
        } elseif($type == 2){ // tháng có doanh số cao nhất

            $monthHighest = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_real) as sales_real,month'))
                ->whereIn('agent_id',$agentId)->where('month','like','%'.$year.'%')->groupBy('month')->orderBy('sales_real','desc')
                ->first()->month;

            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,products.code,month'))
                ->whereIn('agent_id',$agentId)->where('month','like',$monthHighest)->groupBy('sale_agents.product_id')->orderBy('sales_real','desc')
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();
            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->code,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tháng doanh số cao nhất '.$monthHighest ],200);
        }elseif($type == 3){ // trung bình tháng
            $month = Carbon::now()->format('m-Y');

            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_real) as sales_real,sale_agents.product_id,products.code'))
                ->whereIn('agent_id',$agentId)->groupBy('product_id')->where('month','like','%'.$year.'%')->where('month','<',$month)
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();
            $countMonth = DB::table('sale_agents')->select('month')->whereIn('agent_id',$agentId)
                ->groupBy('month')->where('month','like','%'.$year.'%')->where('month','<',$month)->get()->count();
            $chartData = [];
            foreach ($products as $key => $p){
                $chartData[] = ['name' => $p->code,'y' => round(intval($p->sales_real)/$countMonth,2)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'trung bình '.$countMonth.' tháng' ],200);
        }else { // tong san luong
            $month = Carbon::now()->format('m-Y');
            $products = DB::table('sale_agents')
                ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,sale_agents.product_id,products.code'))
                ->whereIn('agent_id',$agentId)->where('month','like','%'.$year.'%')->where('month','<=',$month)->groupBy('product_id')
                ->join('products','sale_agents.product_id','=','products.id')
                ->get()->toArray();


            $chartData = [];
            foreach ($products as $key => $p){

                $chartData[] = ['name' => $p->code,'y' => intval($p->sales_real)];
            }

            return Response::json(['chart' =>$chartData,'table' => $chartData ,'title' =>'tổng sản lượng đến tháng '.$month ],200);
        }
    }




    public function export(Request $request ) {
        $validator = Validator::make($request->all(), [
            'startMonth' => 'required',
            'endMonth' => 'required',
            'type_data_search' => 'required',
            'data_search' => 'required'
        ]);
        if($validator->fails()) {

            return Response::json(['status' => 0,'errors' => $validator->errors()],200);
        }
        $startMonth = $request->input('startMonth');
        $endMonth = $request->input('endMonth');
        $type = $request->input('type_data_search');
        $user = $request->input('data_search');
//        return view('exportDashboard',compact('startMonth','endMonth','type','user'));
        $this->dispatch(new ExportDashboard( $startMonth,$endMonth,$type,$user,auth()->user()->id));
        return Response::json(['status' => 1,'message' => 'Export trong quá trình chạy.Vui lòng chờ thông báo để tải file'],200);
    }
            public function download(Request $request,$id) {
                $notification = Notification::where('id' ,$id)->where('user_id',auth()->user()->id)->first();
                if(empty($notification)) {
                    return redirect()->back()->with('error','Không thể tải file');
                }

                $link =$notification->content['link'];
                if(File::exists($link)) {
                    return response()->download($link);
        } else {
            return redirect()->back()->with('error','File ko tồn tại');
        }

    }

    public function guiSearch(Request $request) {
        if(auth()->user()->position != \App\Models\User::ADMIN and auth()->user()->position != \App\Models\User::SALE_ADMIN) {
            abort(403);
        }
        $lastMonth = DB::table('sale_agents')
            ->select('*')->orderBy('month','desc')
            ->first()->month;
        $agents = Agent::selectRaw('sum(sale_agents.sales_real)  as sales_real,sale_agents.capacity,agents.*')
            ->join('sale_agents','sale_agents.agent_id','=','agents.id')->where('sale_agents.month',$lastMonth)
            ->groupBy('agents.id')->with('user')->get();


        return view('admin.guiSearch',compact('agents'));
    }
}
