<?php

namespace App\Http\Controllers\Frontend;

use App\Garena\Functions;
use App\Http\Controllers\Controller;

class MainController extends Controller
{

    /**
     * FrontendController constructor.
     */
    public function __construct()
    {
        //hard login
        //Functions::hardLogin();
        $this->middleware('auth.frontend', ['except' => ['index']]);
    }

    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        echo "welcome, ".auth('frontend')->user()->username;
    }
}
