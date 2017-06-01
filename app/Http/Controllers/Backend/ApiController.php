<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AddressGeojson;
class ApiController extends Controller
{

    public function getListAreas(Request $request){

        $places = AddressGeojson::select('*');
        if($request->input('q')){
            $places = $places->where('name','like','%'.$request->input('q').'%');
        }
        $places = $places->limit(5)->get();
        return $places;
    }
}
