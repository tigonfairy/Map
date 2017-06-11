<?php

namespace App\Http\Controllers\Backend;

use App\Models\Agent;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends AdminController
{

    public function index()
    {
       return view('admin.index');
    }
    public function dashboard(Request $request){


        $month = Carbon::now()->format('m-Y');

        if($request->input('month')){
            $month = $request->input('month');
        }

        $listArea = Area::all();
        $areas = $listArea;
        if($request->input('area_id')){
            $listArea = $listArea->where('id', $request->input('area_id'));
        }

        $dataSales = $listArea->map( function ($area) use ($month) {
            $listAgentIds = $area->agent()->pluck('id');
            return [
                'area' => $area->name,
                'id' => $area->id,
                'data' => Agent::getDataSales($listAgentIds, $month),
                ];
        });



        return view('admin.dashboard', compact('dataSales', 'month', 'areas'));
    }
}
