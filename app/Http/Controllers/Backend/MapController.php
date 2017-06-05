<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\Area;
use App\Models\Agent;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Models\AddressGeojson;
use App\Http\Controllers\Controller;

class MapController extends Controller
{

    public function index()
    {

        if (auth()->user()->cannot('map')) {
            abort(403);
        }
        $locations = AddressGeojson::all();
        return view('admin.map.index',compact('locations'));
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
        AddressGeojson::create(['name' => $data['name'],'slug' => $slug, 'coordinates' => $coordinates]);
    }
    public function listMapUser(Request $request){
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
        $locations = $area->address;
        return view('admin.map.mapUserDetail',compact('area','locations'));
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
        $area = Area::create($data);
        if($data['place']){
            $area->address()->sync($data['place']);
        }
        return redirect()->route('Admin::map@listMapUser')->with('success','Tạo vùng kinh doanh thành công');
    }

    public function listAgency(Request $request){
        $agents = Agent::select('*');
        if($request->input('q')){
            $key = $request->input('q');
            $agents = $agents->where('name','like','%'.$key.'%');
        }
        $agents = $agents->paginate(10);
        return view('admin.map.listAgency',compact('agents'));
    }
    public function addAgency(Request $request){
        $users = User::all();
        return view('admin.map.addAgency',compact('users'));
    }
    public function addMapAgencyPost(Request $request){
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
        $data = $request->all();
        $agent = Agent::create($data);
        return redirect()->route('Admin::map@listMapUser')->with('success','Tạo đại lý thành công');
    }





    public function addDataAgency(Request $request){
        $agents = Agent::all();
        $products = Product::all();
        return view('admin.map.addDataAgency',compact('agents', 'products'));
    }
    public function addDataAgencyPost(Request $request){
        $this->validate($request,[
            'agent_id' => 'required',
            'product_id' => 'required',
            'month' => 'required',
            'sales_plan' => 'required',
            'sales_real' => 'required',
        ],[
            'agent_id.required' => 'Vui lòng chọn đại lý',
            'product_id.required' => 'Vui lòng chọn nhóm sản phẩm',
            'month.required' => 'Vui lòng chọn thời gian',
            'sales_plan.required' => 'Vui lòng nhập doanh số kế hoạch',
            'sales_real.required' => 'Vui lòng nhập doanh số thực tế',
        ]);
        $data = $request->all();
        SaleAgent::create($data);
        return redirect()->route('Admin::map@listMapUser')->with('success','Tạo dữ liệu cho đại lý thành công');
    }


}
