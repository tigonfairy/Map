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

        $dataSales = [];
        $month = Carbon::now()->format('m-Y');
        $listArea = Area::all();
        $areas = $listArea;

        if($request->input('month')){
            $month = $request->input('month');
        }

        if($request->input('area_id')){
            $listAreaChild = Area::where('parent_id', $request->input('area_id'))->pluck('id');
            if(count($listAreaChild) > 0) {
                $listArea = $listArea->whereIn('id', $listAreaChild);
            } else {
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
        }

        return view('admin.dashboard', compact('dataSales', 'month', 'areas'));
    }
}
