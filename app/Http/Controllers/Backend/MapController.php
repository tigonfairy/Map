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

        $provinces = AddressGeojson::groupBy('province')->pluck('province');

        return view('admin.map.index',compact('provinces'));
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

}
