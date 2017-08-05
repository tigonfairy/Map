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
class MapController extends AdminController
{
    public function listLocation()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return view('admin.map.index');
    }

    public function addMap(){
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return view('admin.map.addMap');
    }

    public function addMapPost(Request $request){
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
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
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $addressGeojson = AddressGeojson::findOrFail($id);

        return view('admin.map.editMap',compact('addressGeojson'));
    }

    public function editMapPost(Request $request, $id){
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
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
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
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
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $area = Area::findOrFail($id);
        $users = User::all();
        $areaAddress =  $area->address;

        return view('admin.map.addMapUser',compact('users','area','areaAddress'));
    }

    public function editMapUserPost(Request $request,$id){
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

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
        $role = $user->roles()->first();
        if ($role->id == 3) {
            abort(403);
        }
        $areas = Area::select('*');
        if($request->input('q')){
            $key = $request->input('q');
            $areas = $areas->where('name','like','%'.$key.'%');
        }
        if($role->id == 1){ // tong vung
            $areas = $areas->paginate(10);
        }else{
            $areas = $areas->where('manager_id',$user->id)->paginate(10);
        }

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
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $users = User::all();
        return view('admin.map.addMapUser',compact('users'));
    }

    public function addMapUserPost(Request $request){
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

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
        if($role->id == 1){
            $agents = $agents->paginate(10);
        }else{
            if(Cache::has('agentId')){
                $agentID = Cache::get('agentId');
            }else{
                $agentID = User::getListAgencyByRole();
            }

            $agents = $agents->whereIn('id',$agentID)->paginate(10);
        }


        return view('admin.map.listAgency',compact('agents'));
    }

    public function addAgency(Request $request){

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

    public function dataSearch(Request $request) {

        $typeSearch = $request->input('type_search');
        $dataSearch = $request->input('data_search');

        if($typeSearch == 'areas') {
            $area = Area::findOrFail($dataSearch);
            $listIds=[];
            $listIds = $area->subArea()->get()->pluck('id')->toArray();
            $listIds[] = $dataSearch;
            $locations = $area->address;
            $agents = Agent::whereIn('area_id',$listIds)->get();

            if($agents){
                $idAgent = clone $agents;
                $idAgent = $idAgent->pluck('id')->toArray();
            }

            return response()->json([
                'area' =>  $area,
                'locations' =>  $locations,
                'agents' =>  $agents,
            ]);
        }

        if($typeSearch == 'sale_admins') {
            $user = User::findOrFail($dataSearch);
            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $areas = $userOwns->map(function ($user) {
                return $user->area()->get();
            });

            $locations=[];
            $listIds=[];
            foreach ($areas as $key => $areaIds) {
                foreach ($areaIds as $k => $area) {
                    array_push($listIds, $area->id);
                    array_push($locations, $area->address);
                }
            }
            $agents = Agent::whereIn('area_id',$listIds)->with('area')->with('user')->get();

            return response()->json([
                'locations' =>  $locations,
                'agents' =>  $agents,
            ]);
        }

        if($typeSearch == 'sale_mans') {
            $agents = Agent::where('manager_id',$dataSearch)->get();
            $areas=[];
            foreach ($agents as $key => $agent) {
                array_push($areas, $agent->area);
            }
            $locations=[];
            foreach ($areas as $key => $area) {
                array_push($locations, $area->address);
            }

            return response()->json([
                'locations' =>  $locations,
                'agents' =>  $agents,
            ]);
        }

        if($typeSearch == 'agents') {
            $agent = Agent::findOrFail($dataSearch);
            $area=$agent->area;
            $user=$agent->user;
            $locations=$area->address;

            return response()->json([
                'area' =>  $area,
                'user' => $user,
                'locations' =>  $locations,
                'agents' =>  $agent,
            ]);
        }
    }

    public function getDatatables() {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return AddressGeojson::getDatatables();
    }
}
