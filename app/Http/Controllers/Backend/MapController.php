<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\AddressGeojson;
use App\Models\Area;
use App\Models\AreaUser;
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
}
