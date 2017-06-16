<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->roles->first()['email'] != 'admin@gamil.com') {
            abort(403);
        }
        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }
        return view('admin.config.index', compact('config'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['email'] != 'admin@gamil.com') {
            abort(403);
        }
        $this->validate($request,[
            'repassword' =>'required',
            'recaptcha' =>'required',
        ]);

        $data = [ 'repassword' => $request->input('repassword', 0),
                  'recaptcha' => $request->input('recaptcha')
                ];

        file_put_contents(public_path().'/config/config.json', json_encode($data));
        return redirect()->route('Admin::config@index')
            ->with('success', 'Đã cập nhật thành công');
    }
}
