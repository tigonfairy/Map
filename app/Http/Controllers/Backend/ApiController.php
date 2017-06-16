<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Area;
use App\Models\User;
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

        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 1);
        });

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }
        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListSaleMans(Request $request){

        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 2);
        });

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }
        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListAgents(Request $request){

        $agents = Agent::select('*');
        if($request->input('q')){
            $agents = $agents->where('name','like','%'.$request->input('q').'%');
        }
        $agents = $agents->orderBy('id','desc')->limit(50)->get();
        return $agents;
    }

}
