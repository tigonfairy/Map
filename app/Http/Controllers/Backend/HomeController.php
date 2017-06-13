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
        $user = auth()->user();


        return view('admin.dashboard', compact('month'));
    }
}
