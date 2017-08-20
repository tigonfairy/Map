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
        $coordinates = json_decode($data['coordinates'],true);
        $newCoordinates = [];
        foreach ($coordinates as $coor){
            $c = explode(",", $coor);
            array_push($newCoordinates, $c);
        }
        $coordinates = json_encode($newCoordinates);
        $address = AddressGeojson::create(['name' => $data['name'],'slug' => $slug, 'coordinates' => $coordinates]);

        return redirect()->back()->with('success','Tạo vùng địa lý thành công');
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
        $coordinates = json_decode($data['coordinates'],true);
        $newCoordinates = [];
        foreach ($coordinates as $coor){
            $c = explode(",", $coor);
            array_push($newCoordinates, $c);
        }
        $coordinates = json_encode($newCoordinates);
        $address->update(['name' => $data['name'],'slug' => $slug, 'coordinates' => $coordinates]);

        return edirect()->route('Admin::map@listLocation')->with('success','Cập nhật vùng địa lý thành công');
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

            $areas = $areas->where('manager_id',$user->id)->paginate(10);


        return view('admin.map.listMapUser',compact('areas'));
    }

    public function mapUserDetail(Request $request,$id){
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
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
            ->select(\DB::raw('SUM(sales_plan) as sales_plan,SUM(sales_real) as sales_real,product_id,products.name'))
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
            $areas = Area::all();


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
        if(isset($data['icon'])){
            $icon = $data['icon'];
            Image::make(public_path($icon))->resize(22, 32)->save(public_path($icon));
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
        $role = $user->roles()->first();

        if($role->id == 1){
            $users = User::all();
            $areas = Area::all();
        }else{
            $users = $user->manager()->get();
            $users->push($user);
            $managerIds = $users->pluck('id')->toArray();
            $areas = Area::all()->whereIn('manager_id', $managerIds);
        }

        return view('admin.map.addAgency',compact('users','agent','areas'));
    }

    public function editAgentPost(Request $request,$id){
        $data = $request->all();
        $this->validate($request,[
            'manager_id' => 'required',
            'name' => 'required',
            'area_id' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ],[
            'manager_id.required' => 'Vui lòng chọn nhân viên quản lý',
            'name.required' => 'Vui lòng nhập tên cho đại lý',
            'area_id.required' => 'Vui lòng chọn vùng trực thuộc',
            'lat.required' => 'Vui lòng chọn đại lý',
            'lng.required' => 'Vui lòng chọn đại lý'
        ]);
        if(isset($data['icon'])){
            $icon = $data['icon'];
            Image::make(public_path($icon))->resize(22, 32)->save(public_path($icon));
        }

        $agent = Agent::findOrFail($id);

        $agent->update($data);
        return redirect()->route('Admin::map@listAgency')->with('success','Sửa đại lý thành công');
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
        $month = $request->input('month');

        if ($typeSearch == 'agents' || $typeSearch == 'nvkd' || $typeSearch == '') {

            if ($typeSearch == 'nvkd' || $dataSearch == 0 || $typeSearch == '') {
                $user = auth()->user();
                $agent = Agent::where('manager_id', $user->id)->first();
            } else {
                $agent = Agent::findOrFail($dataSearch);
            }

            $totalSales = 0;
            $saleProducts = 0;
            $listProducts = [];
            $capacity = 0;

            $productParents = Product::getParent();

            foreach ($productParents as $product) {
                foreach ($product->getChildren as $p) {
                    if (isset($p->code)) {
                        $sales = SaleAgent::where('agent_id', $agent->id)->where('product_id', $p->id)->where('month', $month)->select('sales_real', 'capacity')->first();
                        if ($sales) {
                            $saleProducts += $sales->sales_real;
                            $capacity = $sales->capacity;
                            $listProducts[] = [
                                'id' => $p->id,
                                'name' => $p->code . ' - ' . $p->name_vn,
                                'code' => $p->code,
                                'totalSales' => $sales->sales_real,
                                'percent' => round($sales->sales_real / $capacity, 2),
                                'capacity' => $capacity
                            ];
                        }
                    }
                }
                $totalSales += $saleProducts;
                $saleProducts = 0;
            }
            $capacity = $capacity == 0 ? 1 : $capacity;
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round($totalSales / $capacity, 2),
                'capacity' => $capacity
            ];

            $user = $agent->user;
            $gsv = $user->manager;
            $gdv = $gsv->manager;

            return response()->json([
                'capacity' => $capacity,
                'user' => $user,
                'gsv' => $gsv,
                'gdv' => $gdv,
                'agents' => $agent,
                'listProducts' => $listProducts
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

            $areas = $user->area()->get();

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

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

            foreach ($agents as $agent) {

                $sales = SaleAgent::where('agent_id', $agent->id)->where('month', $month)->select('sales_real', 'capacity')->get();
                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;
                    $capacity = $sale->capacity;
                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round($saleAgents / $capacity, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }

            return response()->json([
                'user' => $user,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round($totalSales / $capacity, 2)
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

            $areas = $userTv->area()->get();

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

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

            foreach ($agents as $agent) {

                $sales = SaleAgent::where('agent_id', $agent->id)->where('month', $month)->select('sales_real', 'capacity')->get();

                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;
                    $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round($saleAgents / $capacity, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }

            return response()->json([
                'user' => $userTv,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round($totalSales / $capacity, 2)
            ]);
        }

        if ($typeSearch == 'gdv') {
            $totalSaleGSV = 0;
            $totalSaleGDV = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            $data = [];
            $locations = [];
            $userGdv = User::findOrFail($dataSearch);
            $userGSV = $userGdv->owners()->get();

            foreach ($userGSV as $user) {
                if ($user->position == User::GSV) { // gsv
                    if (count($user->owners) > 0) {
                        $listIds = $user->owners->pluck('id')->toArray();
                        $listIds[] = $user->id;
                    }
                } else if ($user->position == User::TV) {
                    if (count($user->owners) > 0) {
                        foreach ($user->owners as $u) {
                            if (count($u->owners) > 0) {
                                $listIds = $u->owners->pluck('id')->toArray();
                            }
                            $listIds[] = $u->id;
                        }
                    }
                }
                $listIds[] = $user->id;
                $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

                foreach ($agents as $agent) {
                    $sales = SaleAgent::where('agent_id', $agent->id)->where('month', $month)->select('sales_real', 'capacity')->get();

                    foreach ($sales as $sale) {
                        $saleAgents += $sale->sales_real;
                        $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                    }
                    $capacity = $capacity == 0 ? 1 : $capacity;
                    $listAgents[] = [
                        'agent' => $agent,
                        'totalSales' => $saleAgents,
                        'capacity' => $capacity,
                        'percent' => round($saleAgents / $capacity, 2)
                    ];
                    $totalSaleGSV += $saleAgents;
                    $saleAgents = 0;
                }
                $listIds = [];
                $totalSaleGDV += $totalSaleGSV;

                $data[] = [
                    'gsv' => $user->name,
                    'agents' => $agents,
                    'totalSales' => $totalSaleGSV,
                    'capacity' => $capacity,
                    'percent' => round($totalSaleGSV / $capacity, 2)
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

            return response()->json([
                'user' => $userGdv,
                'result' => $data,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSaleGDV,
                'capacity' => $capacity,
                'percent' => round($totalSaleGDV / $capacity, 2)
            ]);
        }

        if ($typeSearch == 'admin') {
            $userGDVs = User::where('position', User::GĐV)->get();

            foreach ($userGDVs as $gdv) {
                $totalSaleGDV = 0;

                foreach ($gdv->owners as $user) {
                    $totalSaleGSV = 0;
                        if ($user->position == User::GSV) { // gsv
                            if (count($user->owners) > 0) {
                                $listIds = $user->owners->pluck('id')->toArray();
                                $listIds[] = $user->id;
                            }
                        } else if ($user->position == User::TV) {
                            if (count($user->owners) > 0) {
                                foreach ($user->owners as $u) {
                                    if (count($u->owners) > 0) {
                                        $listIds = $u->owners->pluck('id')->toArray();
                                    }
                                    $listIds[] = $u->id;
                                }
                            }
                        }
                        $listIds[] = $user->id;

                        $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
                        $saleAgents = 0;
                        foreach ($agents as $agent) {
                            $sales = SaleAgent::where('agent_id', $agent->id)->where('month', $month)->select('sales_real', 'capacity')->get();

                            foreach ($sales as $sale) {
                                $saleAgents += $sale->sales_real;
                                $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                            }
                            $capacity = $capacity == 0 ? 1 : $capacity;
                            $listAgents[] = [
                                'agent' => $agent,
                                'totalSales' => $saleAgents,
                                'capacity' => $capacity,
                                'percent' => round($saleAgents / $capacity, 2)
                            ];
                            $totalSaleGSV += $saleAgents;
                            $saleAgents = 0;
                        }
                        $listIds = [];
                        $totalSaleGDV += $totalSaleGSV;

//                        $data[] = [
//                            'gsv' => $user->name,
//                            'agents' => $agents,
//                            'totalSales' => $totalSaleGSV,
//                            'capacity' => $capacity,
//                            'percent' => round($totalSaleGSV / $capacity, 2)
//                        ];


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

                $data[] = [
                    'gdv' => $gdv->name,
                    'agents' => $agents,
                    'totalSales' => $totalSaleGDV,
                    'capacity' => $capacity,
                    'percent' => round($totalSaleGDV / $capacity, 2)
                ];
                dd($data);
            }


//            $listIds = $users->pluck('id')->toArray();
//            foreach ($users as $user) {
//                foreach ($user->area as $key => $area) {
//                    foreach ($area->address as $k => $address) {
//                        $locations[] = [
//                            'border_color' => $area->border_color,
//                            'background_color' => $area->background_color,
//                            'area' => $address
//                        ];
//                    }
//                }
//            }
//
//            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

            return response()->json([
                'locations' => $locations,
                'agents' => $agents
            ]);

        }
    }

    public function getDatatables() {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
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
            $this->dispatch(new ImportAgent( storage_path('app/import/agents/' . $filename),$name));

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
