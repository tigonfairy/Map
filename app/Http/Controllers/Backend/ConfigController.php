<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ConfigController extends AdminController
{
    public function index(Request $request)
    {

        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }
        return view('admin.config.index', compact('config'));
    }

    public function store(Request $request)
    {
        $config = [];
        if (file_exists(public_path().'/config/config.json')) {
            $config = json_decode(file_get_contents(public_path().'/config/config.json'),true);
        }
        $config['repassword'] = $request->input('repassword', 0);
        $config['recaptcha'] = $request->input('recaptcha',2);
        $config['textColor'] =$request->input('textColor', '#FF0000');
        $config['fontSize'] =$request->input('fontSize', '15');

        if($request->file('agent_diamond')) {
            $old = (isset($config['agent_diamond'])) ? $config['agent_diamond'] : null;
            $agent_diamond = $this->saveImage($request->file('agent_diamond'),$old,'agent_diamond');
            $config['agent_diamond'] = $agent_diamond;
        }

        if($request->file('agent_gold')) {
            $old = (isset($config['agent_gold'])) ? $config['agent_gold'] : null;
            $agent_gold = $this->saveImage($request->file('agent_gold'),$old,'agent_gold');
            $config['agent_gold'] = $agent_gold;
        }

        if($request->file('agent_silver')) {
            $old = (isset($config['agent_silver'])) ? $config['agent_silver'] : null;
            $agent_silver = $this->saveImage($request->file('agent_silver'),$old,'agent_silver');
            $config['agent_silver'] = $agent_silver;
        }

        if($request->file('agent_unclassified')) {
            $old = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
            $agent_unclassified = $this->saveImage($request->file('agent_unclassified'),$old,'agent_unclassified');
            $config['agent_unclassified'] = $agent_unclassified;
        }

        if($request->file('agent_rival')) {
            $old = (isset($config['agent_rival'])) ? $config['agent_rival'] : null;
            $agent_rival = $this->saveImage($request->file('agent_rival'),$old,'agent_rival');
            $config['agent_rival'] = $agent_rival;
        }
        file_put_contents(public_path().'/config/config.json', json_encode($config));
        return redirect()->route('Admin::config@index')
            ->with('success', 'Đã cập nhật thành công');
    }
}
