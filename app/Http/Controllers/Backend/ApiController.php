<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AddressGeojson;

class ApiController extends AdminController
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

    public function getGSV(Request $request){

        $users = User::where('position', User::GSV);
        $user = auth()->user();

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }

        if ($user->position == User::TV || $user->position == User::GĐV ) {
            $userOwns = $user->owners()->get();
            $userOwnIds = $userOwns->where('position', User::GSV)->pluck('id')->toArray();

            $users->whereIn('id', $userOwnIds);
        }

        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListGDV(Request $request){
        $users = User::where('position', User::GĐV);

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }
        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListTV(Request $request){

        $users = User::where('position', User::TV);
        $user = auth()->user();

        if($request->input('q')){
            $users = $users->where('name','like','%'.$request->input('q').'%');
        }

        if ($user->position == User::GĐV ) {
            $userOwns = $user->owners()->get();
            $userOwnIds = $userOwns->where('position', User::TV)->pluck('id')->toArray();

            $users->whereIn('id', $userOwnIds);
        }

        $users = $users->orderBy('id','desc')->limit(50)->get();
        return $users;
    }

    public function getListAgents(Request $request){

        $account = auth()->user();

        $agents = Agent::select('*');
        if($request->input('q')){
            $key = $request->input('q');
            $agents = $agents->where('name','like','%'.$key.'%');
        }

        if ($account->position == User::NVKD) {
            $agents->where('manager_id', $account->id);
        } else if ($account->position == User::GSV) {

            $userOwns = $account->owners()->get();
            $userOwns->push($account);
            $listIds = $userOwns->pluck('id')->toArray();
            $agents->whereIn('manager_id', $listIds);
        } else if ($account->position == User::TV) {
            $userOwns = $account->owners()->get();
            foreach ($userOwns as $user) {
                if (count($user->owners) > 0) {
                    foreach ($user->owners as $u) {
                        $userOwns->push($u);
                    }
                } else {
                    $userOwns->push($user);
                }
            }
            $userOwns->push($account);

            $listIds = $userOwns->pluck('id')->toArray();

            $agents->whereIn('manager_id', $listIds);

        } else if ($account->position == User::GĐV) {
            $userGSV = $account->owners()->get();

            $listIds = [];
            foreach ($userGSV as $user) {
                if ($user->position == User::GSV) { // gsv
                    if (count($user->owners) > 0) {

                        foreach ($user->owners as $us) {
                            $listIds[] = $us->id;
                        }
                    }
                } else if ($user->position == User::TV) { // tv
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
            }

            $agents = Agent::whereIn('manager_id', $listIds);

        }

//        if($role->id == 1){
//            $agents = $agents->paginate(10);
//        }else{
//            $userOwns = $user->manager()->get();
//            $userOwns->push($user);
//            $managerIds = $userOwns->pluck('id')->toArray();
//            $agents = Agent::whereIn('manager_id', $managerIds);
//            $agents = $agents->paginate(10);
//        }


        $agents = $agents->paginate(10);
        return $agents;
    }

}
