<?php

namespace App\Http\Controllers\Backend;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Area;
use App\Models\Agent;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Models\AddressGeojson;
use Auth;
use Illuminate\Support\Facades\Cache;
use Image;
use Validator;
use Excel;
use App\Jobs\ImportAgent;
class MapController extends AdminController
{
    public function listLocation()
    {
        return view('admin.map.index');
    }

    public function addMap(){

        return view('admin.map.addMap');
    }

    public function addMapPost(Request $request){

        $this->validate($request,[
           'name' => 'required',
            'coordinates' => 'required'
        ],[
            'name.required' => 'Vui lòng nhập tên',
            'coordinates.required' => 'Chưa vẽ vùng địa lý'
        ]);
        $data=$request->all();
        $slug = str_slug($data['name']);
        $count = AddressGeojson::where("slug",$slug)->count();
        if($count > 0){
            $slug = $slug.time();
        }
        $arrayCor = json_decode($data['coordinates'],true);
        $FNcoordinates = [];

        if(count($arrayCor)) {
            foreach ($arrayCor as $a ) {
                $coordinates = json_decode($a,true);
                $newCoordinates = [];
                foreach ($coordinates as $coor){

                    $c = explode(",", $coor);

                    $c[0] = doubleval(  $c[0]);
                    $c[1] = doubleval(  $c[1]);
                    array_push($newCoordinates, $c);
                }
                $FNcoordinates[] = $newCoordinates;

            }

        }

        $dataUpdate = ['name' => $data['name'],'slug' => $slug];
        if(count($dataUpdate)) {
            $dataUpdate['coordinates'] = json_encode($FNcoordinates);
        }

        $address = AddressGeojson::create($dataUpdate);

        return redirect()->route('Admin::map@listLocation')->with('success','Tạo vùng địa lý thành công');
    }

    public function editMap(Request $request,$id){
        $addressGeojson = AddressGeojson::findOrFail($id);

        return view('admin.map.editMap',compact('addressGeojson'));
    }

    public function editMapPost(Request $request, $id){
        $this->validate($request,[
            'name' => 'required',
            'coordinates' => 'required'
        ],[
            'name.required' => 'Vui lòng nhập tên',
            'coordinates.required' => 'Chưa vẽ vùng địa lý'
        ]);
        $address = AddressGeojson::find($id);

        $data=$request->all();
        $slug = str_slug($data['name']);

        $arrayCor = json_decode($data['coordinates'],true);
        $FNcoordinates = [];

        if(count($arrayCor)) {
            foreach ($arrayCor as $a ) {
                $coordinates = json_decode($a,true);
                $newCoordinates = [];
                foreach ($coordinates as $coor){

                    $c = explode(",", $coor);

                    $c[0] = doubleval(  $c[0]);
                    $c[1] = doubleval(  $c[1]);
                    array_push($newCoordinates, $c);
                }
                $FNcoordinates[] = $newCoordinates;

            }

        }

        $dataUpdate = ['name' => $data['name'],'slug' => $slug];
        if(count($dataUpdate)) {
            $dataUpdate['coordinates'] = json_encode($FNcoordinates);
        }

        $address->update($dataUpdate);

        return redirect()->route('Admin::map@listLocation')->with('success','Cập nhật vùng địa lý thành công');
    }

    public function deleteMap(Request $request,$id){

        $address = AddressGeojson::find($id);
        if($address){
            $address->delete();
            DB::table('area_address')->where('address_id', $id)->delete();
            return redirect()->back()->with('success','Xóa thành công!!');
        }else{
            return redirect()->back()->with('error','Không tồn tại !!');
        }

    }

    public function editMapUser(Request $request,$id){


        $area = Area::findOrFail($id);
        $users = User::all();
        $areaAddress =  $area->address;

        return view('admin.map.addMapUser',compact('users','area','areaAddress'));
    }

    public function editMapUserPost(Request $request,$id){

        $area = Area::findOrFail($id);
        $this->validate($request,[
            'manager_id' => 'required',
            'place' => 'required'
        ],[
            'manager_id.required' => 'Vui lòng chọn nhân viên quản lý',
            'place.required' => 'Vui lòng chọn vùng quản lý'
        ]);
        $data=$request->all();
        $area->update($data);
        if($data['place']){
            $area->address()->sync($data['place']);
        }
        return redirect()->route('Admin::map@listMapUser')->with('success','Sửa vùng kinh doanh thành công');

    }

    public function listMapUser(Request $request){
        $user = auth()->user();

        $areas = Area::select('*');
        if($request->input('q')){
            $key = $request->input('q');
            $areas = $areas->where('name','like','%'.$key.'%');
        }
        $areas = $areas->paginate(10);



        return view('admin.map.listMapUser',compact('areas'));
    }

    public function mapUserDetail(Request $request,$id){

        $area = Area::findOrFail($id);
        $month = Carbon::now()->format('m-Y');
        if($request->input('month')){
            $month = $request->input('month');
        }

        $listIds = $area->subArea()->get()->pluck('id')->toArray();
        $listIds[] = $id;
        $locations = $area->address;
        $agents = Agent::whereIn('area_id',$listIds)->get();
        if($agents){
            $idAgent = clone $agents;
            $idAgent = $idAgent->pluck('id')->toArray();
        }

        $products = DB::table('sale_agents')
            ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,sale_agents.product_id'))
            ->groupBy('product_id')
            ->whereIn('agent_id',$idAgent)->where('month',$month)
            ->join('products','sale_agents.product_id','=','products.id')
            ->get();
        return view('admin.map.mapUserDetail',compact('area','locations','agents','month','products'));
    }

    public function addMapUser(){

        $users = User::all();
        return view('admin.map.addMapUser',compact('users'));
    }

    public function addMapUserPost(Request $request){

        $this->validate($request,[
            'manager_id' => 'required',
            'place' => 'required'
        ],[
            'manager_id.required' => 'Vui lòng chọn nhân viên quản lý',
            'place.required' => 'Vui lòng chọn vùng quản lý'
        ]);
        $data=$request->all();
        if(isset($data['parent_id'])){
            $data['parent_id'] = intval($data['parent_id']);
        }
        $area = Area::create($data);
        if($data['place']){
            $area->address()->sync($data['place']);
        }
        return redirect()->route('Admin::map@listMapUser')->with('success','Tạo vùng kinh doanh thành công');
    }

    public function listAgency(Request $request){

        $user = auth()->user();
        $role = $user->roles()->first();

        $agents = Agent::select('*');
        if($request->input('q')){
            $key = $request->input('q');
            $agents = $agents->where('name','like','%'.$key.'%');
        }

            $agents = $agents->paginate(10);


        return view('admin.map.listAgency',compact('agents'));
    }

    public function addAgency(Request $request){

        $user = auth()->user();
            $users = User::all();
        $areas =null;
//            $areas = Area::all();


        return view('admin.map.addAgency',compact('users','areas'));
    }

    public function addMapAgencyPost(Request $request){
        $this->validate($request,[
            'manager_id' => 'required',
            'area_id' => 'required',
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ],[
            'manager_id.required' => 'Vui lòng chọn nhân viên quản lý',
            'area_id.required' => 'Vui lòng chọn vùng trực thuộc',
            'name.required' => 'Vui lòng nhập tên cho đại lý',
            'lat.required' => 'Vui lòng chọn đại lý',
            'lng.required' => 'Vui lòng chọn đại lý',
        ]);

        $data = $request->all();
        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }
        $data['icon'] = '';
        if(isset($data['rank'])) {
            $rank = $data['rank'];
            if($rank == Agent::diamond) {
                $data['icon'] = (isset($config['agent_diamond'])) ? $config['agent_diamond'] : null;
            }
            if($rank == Agent::gold) {
                $data['icon'] = (isset($config['agent_gold'])) ? $config['agent_gold'] : null;
            }
            if($rank == Agent::silver) {
                $data['icon'] = (isset($config['agent_silver'])) ? $config['agent_silver'] : null;
            }
            if($rank == Agent::unclassified) {
                $data['icon'] = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
            }
        }
        if(isset($data['attribute']) and $data['attribute'] == Agent::agentRival) {
            $data['icon'] = (isset($config['agent_rival'])) ? $config['agent_rival'] : null;

        }
        if($data['manager_id']) {
            $data['gdv'] = 0;
            $data['pgdkd'] = 0;
            $data['tv'] = 0;
            $data['gsv'] = 0;

            $manager_id = $data['manager_id'];
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


        }


        Agent::create($data);
        return redirect()->route('Admin::map@listAgency')->with('success','Tạo đại lý thành công');
    }

    public function addDataAgency(Request $request){
        $agents = Agent::all();
        $products = Product::all();
        return view('admin.map.addDataAgency',compact('agents', 'products'));
    }

    public function addDataAgencyPost(){
        $this->validate(request(),[
            'agent_id' => 'required',
            'month' => 'required',
        ],[
            'agent_id.required' => 'Vui lòng chọn đại lý',
            'month.required' => 'Vui lòng chọn thời gian',
        ]);

        $product_ids = request('product_id');
        $sales_plan = request('sales_plan');
        $sales_real = request('sales_real');

        foreach ($product_ids as $key => $product_id) {
            $sale = SaleAgent::firstOrCreate([
                'agent_id' => request('agent_id'),
                'product_id' => $product_id,
                'month' => request('month'),
            ]);
            $sale->sales_plan = $sales_plan[$key] ? $sales_plan[$key] : 0;
            $sale->sales_real = $sales_real[$key] ? $sales_real[$key] : 0;
            $sale->save();
        }

        return redirect()->route('Admin::map@listAgency')->with('success','Tạo dữ liệu cho đại lý thành công');
    }

    public function agentDetail(Request $request,$id){
        $agent = Agent::find($id);
        $month = Carbon::now()->format('m-Y');
        if($request->input('month')){
            $month = $request->input('month');
        }
        $products = $agent->product()->where('month',$month)->get();
        return view('admin.map.agentDetail',compact('agent','products','month'));
    }

    public function editAgent(Request $request,$id){

        $agent = Agent::findOrFail($id);
        $user = auth()->user();
        $areas=null;
        $users = User::all();
//        $areas = Area::all();


        return view('admin.map.addAgency',compact('users','agent','areas'));
    }

    public function editAgentPost(Request $request,$id){
        $data = $request->all();
        $this->validate($request,[
            'manager_id' => 'required',
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ],[
            'manager_id.required' => 'Vui lòng chọn nhân viên quản lý',
            'name.required' => 'Vui lòng nhập tên cho đại lý',
            'lat.required' => 'Vui lòng chọn đại lý',
            'lng.required' => 'Vui lòng chọn đại lý'
        ]);
        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }
        $data['icon'] = '';
        if(isset($data['rank'])) {
            $rank = $data['rank'];
            if($rank == Agent::diamond) {
                $data['icon'] = (isset($config['agent_diamond'])) ? $config['agent_diamond'] : null;
            }
            if($rank == Agent::gold) {
                $data['icon'] = (isset($config['agent_gold'])) ? $config['agent_gold'] : null;
            }
            if($rank == Agent::silver) {
                $data['icon'] = (isset($config['agent_silver'])) ? $config['agent_silver'] : null;
            }
            if($rank == Agent::unclassified) {
                $data['icon'] = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
            }
        }
        if(isset($data['attribute']) and $data['attribute'] == Agent::agentRival) {
            $data['icon'] = (isset($config['agent_rival'])) ? $config['agent_rival'] : null;
        }

        if(isset($data['attribute']) and $data['attribute'] == Agent::agentNew) {
            $data['icon'] = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
        }



        $agent = Agent::findOrFail($id);
        if($data['manager_id'] != $agent->manager_id) {
            $data['gdv'] = 0;
            $data['pgdkd'] = 0;
            $data['tv'] = 0;
            $data['gsv'] = 0;

            $manager_id = $data['manager_id'];
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


        }

        $agent->update($data);
        return redirect()->back()->with('success','Sửa đại lý thành công');
    }

    public function mapUserDelete(Request $request,$id){
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
        $area = Area::find($id);
        if($area){
            $area->address()->sync([]);
            $area->delete();
            return redirect()->back()->with('success','Xóa thành công!!');
        }else{
            return redirect()->back()->with('error','Không tồn tại !!');
        }

    }

    public function agentDelete(Request $request,$id){
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
        $agent = Agent::find($id);
        if($agent){
            $saleAgent = SaleAgent::where('agent_id',$id)->delete();
            $agent->delete();
            return redirect()->back()->with('success','Xóa thành công!!');
        }else{
            return redirect()->back()->with('error','Không tồn tại !!');
        }
    }

    public function search() {

        $areas = Area::all();
        $agents = Agent::all();
        $users = User::all();

        return view('admin.map.search', compact('areas', 'agents', 'users'));
    }

    public function dataSearch(Request $request)
    {

        $typeSearch =$request->input('type_search');
        $dataSearch = $request->has('data_search') ? $request->input('data_search') : 0;

        $startMonth = $request->input('startMonth');
        $startMonth = '01-'.$startMonth;
        $startMonth = Carbon::parse($startMonth)->format('Y-m-d');

        $endMonth = $request->input('endMonth');
        $endMonth = '01-'.$endMonth;
        $endMonth = Carbon::parse($endMonth)->format('Y-m-d');

        if ($typeSearch == 'agents') {
            $agent = Agent::findOrFail($dataSearch);
            $totalSales = 0;
            $listProducts = [];

            $listCodes = [];

            $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->groupBy('agent_id','month')->where('agent_id',$agent->id)
                ->join('agents','agents.id', '=' ,'sale_agents.agent_id')
                ->get()->sum('capacity');

            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::where('agent_id', $agent->id)->where('product_id', $product->id)
                                ->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->select(DB::raw("SUM(sales_real) as sales_real"), "capacity")->first();
                            if (!is_null($sales->sales_real)) {
                                $slGroup += $sales->sales_real;

                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sales_real,
                                    'percent' => round(($sales->sales_real / $capacity) * 100, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }
                    $capacity = $capacity != 0 ? $capacity : 1;
                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round(($slGroup / $capacity) * 100, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                    $totalSales += $slGroup;
                }
            }
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'capacity' => $capacity,
            ];
            // table data
            $type = 1;
            $user = $agent->id;
            $table = view('tableDashboard', compact('type', 'user', 'startMonth', 'endMonth'))->render();
            $nvkd = $agent->user;
            $gsv = $nvkd->manager;
            $gdv = $gsv->manager;
            array_unique($listCodes);
            return response()->json([
                'capacity' => $capacity,
                'user' => $nvkd,
                'gsv' => $gsv,
                'gdv' => $gdv,
                'agents' => $agent,
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
            ]);
        }

        if ($typeSearch == 'nvkd' || $typeSearch == '') {
            $totalSales = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            if ($dataSearch != 0) {
                $user = User::findOrFail($dataSearch);
            } else {
                $user = auth()->user();
            }
            $userParent = $user->manager;
            $gdv = $userParent->manager;
            $areas = $userParent->area()->get();
            $locations = [];
            foreach ($areas as $key => $area) {
                foreach ($area->address as $k => $address) {
                    $locations[] = [
                        'border_color' => $area->border_color,
                        'background_color' => $area->background_color,
                        'area' => $address
                    ];
                }
            }

            $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.attribute', '!=', Agent::agentRival)->where('agents.manager_id',$user->id)
                ->get()->sum('capacity');

            $agents = Agent::where('manager_id', $user->id)->with('user')->get();
            foreach ($agents as $agent) {
                $sales = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)->select('sales_real', 'capacity')->get();
                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;

                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round(($saleAgents / $capacity) * 100, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }
            $capacity = $capacity == 0 ? 1 : $capacity;
            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'capacity' => $capacity
            ];
            $agentIds = $agents->pluck('id')->toArray();
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;
                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round(($sales->sum / $capacity) * 100, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }
                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round(($slGroup / $capacity) * 100, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }
            // table data
            $type = 5;
            $id = $user->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();
            return response()->json([
                'user' => $user,
                'userParent' => $userParent,
                'gdv' => $gdv,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'listProducts' => $listProducts,
                'listCodes' => $listCodes,
                'table' => $table
            ]);
        }

        if ($typeSearch == 'gsv') {
            $totalSales = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            $user = User::findOrFail($dataSearch);
            $userParentName = $user->manager->name;
            $userOwns = $user->owners()->get();
            $userOwns->push($user);
            $listIds = $userOwns->pluck('id')->toArray();
            $areas = $user->area()->first();
            $locations = [];
            foreach ($areas->address as $k => $address) {
                $locations[] = [
                    'border_color' => $areas->border_color,
                    'background_color' => $areas->background_color,
                    'area' => $address
                ];
            }
            $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gsv',$user->id)
                ->get()->sum('capacity');

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
            foreach ($agents as $agent) {
                $sales = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)->select('sales_real', 'capacity')->get();
                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;

                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round(($saleAgents / $capacity) * 100, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }
            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'capacity' => $capacity
            ];
            $agentIds = $agents->pluck('id')->toArray();
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;
                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round(($sales->sum / $capacity) * 100, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }
                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round(($slGroup / $capacity) * 100, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }
            // table data
            $type = 2;
            $id = $user->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();
            array_unique($listCodes);
            return response()->json([
                'user' => $user,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes,
                'area_name' => $areas->name
            ]);
        }

        if ($typeSearch == 'tv') {
            $totalSales = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            $userTv = User::findOrFail($dataSearch);
            $userParentName = $userTv->manager->name;
            $userOwns = $userTv->owners()->get();
            foreach ($userOwns as $u) {
                if (count($u->owners) > 0) {
                    foreach ($u->owners as $us) {
                        $userOwns->push($us);
                    }
                } else {
                    $userOwns->push($u);
                }
            }
            $userOwns->push($userTv);
            $listIds = $userOwns->pluck('id')->toArray();
            $areas = $userTv->area()->first();
            $locations = [];
            foreach ($areas->address as $k => $address) {
                $locations[] = [
                    'border_color' => $areas->border_color,
                    'background_color' => $areas->background_color,
                    'area' => $address
                ];
            }

            $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.tv',$userTv->id)
                ->get()->sum('capacity');

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
            foreach ($agents as $agent) {
                $sales = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)->select('sales_real', 'capacity')->get();
                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;
                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round(($saleAgents / $capacity) * 100, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }
            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'capacity' => $capacity
            ];
            $agentIds = $agents->pluck('id')->toArray();
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;
                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round(($sales->sum / $capacity) * 100, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }
                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round(($slGroup / $capacity) * 100, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }
            // table data
            $type = 3;
            $id = $userTv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();
            array_unique($listCodes);
            return response()->json([
                'user' => $userTv,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round(($totalSales / $capacity) * 100, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes,
                'area_name'=> $areas->name
            ]);
        }

        if ($typeSearch == 'gdv') {

            $totalSaleGSV = 0;
            $totalSaleGDV = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            $data = [];
            $dataGdv = [];
            $locations = [];
            $agentIds = [];


            if ($dataSearch != 0) {
                $userGdv = User::findOrFail($dataSearch);
                $userGSV = $userGdv->owners()->get();
                $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$userGdv->id)
                    ->get()->sum('capacity');
            } else {
                $userGdvs = User::where('position',  User::GĐV)->get();
                foreach ($userGdvs as $userGdv) {
                    foreach ($userGdv->owners as $user) {
                        if ($user->id != $userGdv->id) {
                            $userGSV[] = $user;
                        }
                    }
                }

                $capacity = \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                    ->groupBy('agent_id','month')->join('agents','agents.id', '=' ,'sale_agents.agent_id')->get()->sum('capacity');
            }


            foreach ($userGSV as $user) {

                $listIds = [];
                if ($user->position == User::GSV) { // gsv
                    if (count($user->owners) > 0) {
                        foreach ($user->owners as $u1) {
                            $listIds[] = $u1->id;
                        }

                    }
                } else if ($user->position == User::TV) {

                    if (count($user->owners) > 0) {
                        foreach ($user->owners as $u2) {
                            if (count($u2->owners) > 0) {
                                foreach ($u2->owners as $u3) {
                                    $listIds[] = $u3->id;
                                }
                            }
                            $listIds[] = $u2->id;
                        }
                    }
                }

                $listIds[] = $user->id;

                //agents
                $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
                foreach ($agents as $agent) {
                    $agentIds[] = $agent->id;
                    $saleAgents = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)
                        ->where('month', '<=', $endMonth)->get()->sum('sales_real');

                    $capacity = $capacity == 0 ? 1 : $capacity;
                    $listAgents[] = [
                        'agent' => $agent,
                        'totalSales' => $saleAgents,
                        'capacity' => $capacity,
                        'percent' => round(($saleAgents / $capacity) * 100, 2)
                    ];
                    $totalSaleGSV += $saleAgents;
                    $agent->totalSales = $saleAgents;
                    $agent->capacity = $capacity;
                    $agent->percent = round(($saleAgents / $capacity) * 100, 2);
                }
                $totalSaleGDV += $totalSaleGSV;
                $data[] = [
                    'gsv' => $user,
                    'gdv' => $user->manager,
                    'agents' => $agents,
                    'totalSales' => $totalSaleGSV,
                    'capacity' => $capacity,
                    'percent' => round(($totalSaleGSV / $capacity) * 100, 2)
                ];
                $totalSaleGSV = 0;

                // area
                $areas = $user->area()->get();
                foreach ($areas as $key => $area) {
                    foreach ($area->address as $k => $address) {
                        $locations[] = [
                            'border_color' => $area->border_color,
                            'background_color' => $area->background_color,
                            'area' => $address
                        ];
                    }
                }
            }

            if ($dataSearch != 0 )
            {
                $agents = Agent::where('manager_id', $userGdv->id)->with('user')->get();

                if (count($agents) > 0) {
                    foreach ($agents as $agent) {
                        $agentIds[] = $agent->id;
                        $sales = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)
                            ->where('month', '<=', $endMonth)->select('sales_real', 'capacity')->get();
                        $saleAgents = 0;
                        foreach ($sales as $sale) {
                            $saleAgents += $sale->sales_real;
                        }
                        $capacity = $capacity == 0 ? 1 : $capacity;
                        $agent->totalSales = $saleAgents;
                        $agent->capacity = $capacity;
                        $agent->percent = round(($saleAgents / $capacity) * 100, 2);
                        $dataGdv[] = [
                            'gsv' => $agent->user->name,
                            'agents' => $agent,
                            'totalSales' => $saleAgents,
                            'capacity' => $capacity,
                            'percent' => round(($saleAgents / $capacity) * 100, 2)
                        ];
                        $totalSaleGDV += $saleAgents;
                    }
                }
            }
//            else {
//                $userGDVIds = User::where('position', User::GĐV)->pluck('id')->toArray();
//
//                $agents = Agent::whereIn('manager_id', $userGDVIds)->with('user')->get();
//
//            }

            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSaleGDV,
                'percent' => round(($totalSaleGDV / $capacity) * 100, 2),
                'capacity' => $capacity
            ];

            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            $listCodes = [];

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')
                                ->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;
                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round(($sales->sum / $capacity) * 100, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }
                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round(($slGroup / $capacity) * 100, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }


            // table data
            $type = 4;

            if ($dataSearch != 0) {
                $id = $userGdv->id;
                $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();
            } else {

                $table = view('tableDashboard2', compact('type', 'startMonth', 'endMonth'))->render();
            }


            array_unique($listCodes);
            return response()->json([
                'user' => $dataSearch != 0 ? $userGdv : '' ,
                'result' => $data,
                'resultGdv' => $dataGdv,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSaleGDV,
                'capacity' => $capacity,
                'percent' => round(($totalSaleGDV / $capacity) * 100, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
            ]);
        }

        if ($typeSearch == 'admin') {

            $userGDVs = User::where('position', User::GĐV)->get();
            $data = [];
            $locations = [];
            $listAgentIds = [];
            foreach ($userGDVs as $gdv) {
                $totalSaleGDV = 0;
                $totalSaleGSV = 0;
                $listAgents = [];
                foreach ($gdv->owners as $user) {

                    if ($user->position == User::GSV) { // gsv
                        if (count($user->owners) > 0) {
                            foreach ($user->owners as $u1) {
                                $listIds[] = $u1->id;
                            }
                        }
                    } else if ($user->position == User::TV) {
                        if (count($user->owners) > 0) {
                            foreach ($user->owners as $u2) {
                                if (count($u2->owners) > 0) {
                                    foreach ($u2->owners as $u3) {
                                        $listIds[] = $u3->id;
                                    }
                                }
                                $listIds[] = $u2->id;
                            }
                        }
                    }
                    $listIds[] = $user->id;
                    // area
                    $areas = $user->area()->get();
                    foreach ($areas as $key => $area) {
                        foreach ($area->address as $k => $address) {
                            $locations[] = [
                                'border_color' => $area->border_color,
                                'background_color' => $area->background_color,
                                'area' => $address
                            ];
                        }
                    }
                }
                $listIds[] = $gdv->id;
                $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
                $saleAgents = 0;
                foreach ($agents as $agent) {
                    $listAgentIds[] = $agent->id;
                    $sales = SaleAgent::where('agent_id', $agent->id)->where('month', '>=', $startMonth)
                        ->where('month', '<=', $endMonth)->select('sales_real', 'capacity')->get();
                    foreach ($sales as $sale) {
                        $saleAgents += $sale->sales_real;
                        $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                    }
                    $capacity = isset($capacity) ? $capacity : 1;
                    $listAgents[] = [
                        'agent' => $agent,
                        'gsv' => $agent->user->manager,
                        'gdv' => $gdv,
                        'totalSales' => $saleAgents,
                        'capacity' => $capacity,
                        'percent' => round($saleAgents / $capacity, 2)
                    ];
                    $totalSaleGSV += $saleAgents;
                    $agent->totalSales = $saleAgents;
                    $agent->capacity = $capacity;
                    $agent->percent = round($saleAgents / $capacity, 2);
                    $saleAgents = 0;
                }
                $listIds = [];
                $totalSaleGDV += $totalSaleGSV;
                $data[] = [
                    'gdv' => $gdv,
                    'agents' => $agents,
                    'gsv' => $agent->user->manager,
                    'totalSales' => $totalSaleGDV,
                    'capacity' => $capacity,
                    'percent' => round($totalSaleGDV / $capacity, 2)
                ];
            }
            // table data
            $table = view('tableDashboardAdmin', compact( 'listAgentIds', 'startMonth', 'endMonth'))->render();
            return response()->json([
                'result' => $data,
                'locations' => $locations,
                'table' => $table,
            ]);
        }
    }

    public function getDatatables() {

        return AddressGeojson::getDatatables();
    }


    public function importExcelAgent(Request $request) {
        $validator = Validator::make($request->all(), [
            'file'=>'required|max:50000|mimes:xlsx,csv'
        ]);

        if($validator->fails()) {
            $response['status'] = 'fails';
            $response['errors'] = $validator->errors();
        } else {
            $name =  $request->file('file')->getClientOriginalName();
            $file = request()->file('file');
            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/agents'), $filename);
            $this->dispatch(new ImportAgent( storage_path('app/import/agents/' . $filename),$name,auth()->user()->id));

            flash()->success('Success!', 'Import successfully.');
            $response['status'] = 'success';
        }

        return response()->json($response);
    }
    public function exportAgency() {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        $agents = Agent::all();
        $exportUserArray = null;
        foreach ($agents as $agent){
            $exportUser['Mã số'] = $agent->code;
            $exportUser['Tên đại lý'] = $agent->name;
            $exportUser['Địa chỉ đại lý'] = $agent->address;

            $manager = $agent->user;


            $exportUser['NVKD'] = null;
            $exportUser['Mã NV'] = null;
            $exportUser['Giám sát']  = null;
            $exportUser['Mã GS']  = null;
            $exportUser['Trưởng vùng']  = null;
            $exportUser['Mã TV']  = null;
            $exportUser['Giám đốc vùng'] = null;
            $exportUser['Mã GĐV'] = null;

            if($manager->position == User::NVKD) {
                $exportUser['NVKD'] = $manager->name;
                $exportUser['Mã NV'] = $manager->code;
            }
            if($manager->position == User::GSV) {
                $exportUser['Giám sát'] = $manager->name;
                $exportUser['Mã GS'] = $manager->code;
            }

            if($manager->position == User::TV) {
                $exportUser['Trưởng vùng'] = $manager->name;
                $exportUser['Mã TV'] = $manager->code;
            }
            if($manager->position == User::GĐV) {
                $exportUser['Giám đốc vùng'] = $manager->name;
                $exportUser['Mã GĐV'] = $manager->code;
            }
            $m2 = $manager->manager;
            while(true) {
                if(empty($m2)) {
                    break;
                }
                if($m2->position == User::NVKD) {
                    $exportUser['NVKD'] = $m2->name;
                    $exportUser['Mã NV'] = $m2->code;
                }
                if($m2->position == User::GSV) {
                    $exportUser['Giám sát'] = $m2->name;
                    $exportUser['Mã GS'] = $m2->code;
                }
                if($m2->position == User::TV) {
                    $exportUser['Trưởng vùng'] = $m2->name;
                    $exportUser['Mã TV'] = $m2->code;
                }
                if($m2->position == User::GĐV) {
                    $exportUser['Giám đốc vùng'] = $m2->name;
                    $exportUser['Mã GĐV'] = $m2->code;
                    break;
                }
                $m2 = $m2->manager;
            }
            $exportUser['Thuộc tính']  = null;
            $exportUser['Xếp hạng']  = null;
            if($agent->attribute) {
                if($agent->attribute == Agent::agentNew) {
                    $exportUser['Thuộc tính'] = 'ĐL Mới';
                }
                if($agent->attribute == Agent::agentRival) {
                    $exportUser['Thuộc tính'] = 'ĐL Đối thủ';
                }
            }
            if($agent->rank) {
                $rank = $agent->rank;
                if ($rank == Agent::diamond) {
                    $exportUser['Xếp hạng'] = 'Kim Cương';
                }
                if ($rank == Agent::gold) {
                    $exportUser['Xếp hạng'] = 'Vàng';
                }
                if ($rank == Agent::silver) {
                    $exportUser['Xếp hạng'] = 'Bạc';
                }
                if ($rank == Agent::unclassified) {
                    $exportUser['Xếp hạng'] = 'Chưa xếp hạng';
                }
            }
            $exportUserArray[] = $exportUser;
        }
        ob_end_clean();
        ob_start();
        Excel::create('agent_'.time(), function ($excel) use ($exportUserArray) {

            $excel->sheet('agents', function ($sheet) use ($exportUserArray) {
                $sheet->cell('A1:M1', function($cells) {
                    // call cell manipulation methods
                    $cells->setBackground('#242729');
                    $cells->setFontColor('#ff8000');
                    $cells->setFontWeight('bold');

                });
                $sheet->fromArray($exportUserArray);

            });

        })->download('xlsx');
    }
}
