<?php

namespace App\Http\Controllers\Backend;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends AdminController
{
    public function index(Request $request)
    {
        $logs = Log::orderBy('updated_at', 'DESC')->paginate(50);

        return view('admin.history.index', compact('logs'));
    }

    public function getDatatables()
    {
        return Log::getDatatables();
    }
}
