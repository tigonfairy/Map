<?php

namespace App\Http\Controllers\Backend;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AddressGeojson;
class ApiController extends Controller
{
    public function getListAddress(Request $request){

        $places = AddressGeojson::select('*');
        if($request->input('q')){
            $places = $places->where('name','like','%'.$request->input('q').'%')->orWhere('slug','like','%'.$request->input('q').'%');
        }
        $places = $places->orderBy('id','desc')->limit(50)->get();
        return $places;
    }
    public function getListAreas(Request $request){

        $places = Area::select('*');
        if($request->input('q')){
            $places = $places->where('name','like','%'.$request->input('q').'%');
        }
        $places = $places->orderBy('id','desc')->limit(50)->get();
        return $places;
    }

    public function getListSaleAdmins(Request $request){

        $places = Area::select('*');
        if($request->input('q')){
            $places = $places->where('name','like','%'.$request->input('q').'%');
        }
        $places = $places->orderBy('id','desc')->limit(50)->get();
        return $places;
    }

}
