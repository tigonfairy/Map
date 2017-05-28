<?php

namespace App\Http\Controllers\Backend;

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

    public function getDistricts(Request $request)
    {
        $province = $request->input('province');
        $district = AddressGeojson::where('province',$province)->pluck('district');
        return $district;
    }

    public function getCoordinates(Request $request)
    {
        $province = $request->input('province');
        $district = $request->input('district');
        $coordinates = AddressGeojson::where('province',$province)->where('district',$district)->pluck('coordinates');
        return $coordinates;
    }

    public function addMap(Request $request){
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
}
