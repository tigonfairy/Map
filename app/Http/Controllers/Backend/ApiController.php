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
        $user = auth()->user();
        $role = $user->roles()->first();

        if($request->input('q')){
            $places = $places->where('name','like','%'.$request->input('q').'%');
        }

        if($role->id == 1){
            $places = $places->orderBy('id','desc')->limit(50)->get();
        } else {

            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $managerIds = $userOwns->pluck('id')->toArray();

            $places = $places->whereIn('manager_id', $managerIds);
            $places = $places->orderBy('id','desc')->limit(50)->get();
        }

        return $places;
    }

    public function getListSaleAdmins(Request $request){

        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 2);
        });

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }
        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListSaleMans(Request $request){

        $user = auth()->user();
        $role = $user->roles()->first();

        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        });

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }

        if($role->id == 1) {
            $users = $users->orderBy('id','desc')->limit(50)->get();
        } else {
            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $managerIds = $userOwns->pluck('id')->toArray();
            $users = $users->whereIn('id',$managerIds);
            $users = $users->orderBy('id','desc')->limit(50)->get();
        }

        return $users;
    }

    public function getListAgents(Request $request){

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
            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $managerIds = $userOwns->pluck('id')->toArray();
            $agents = Agent::whereIn('manager_id', $managerIds);
            $agents = $agents->paginate(10);
        }
        return $agents;
    }

}
