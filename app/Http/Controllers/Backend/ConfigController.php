<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Agent;

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
//        $config['textColor'] =$request->input('textColor', '#FF0000');
//        $config['fontSize'] =$request->input('fontSize', '15');
            $newImage = null;
        if($request->file('agent_diamond')) {
            $old = (isset($config['agent_diamond'])) ? $config['agent_diamond'] : null;
            $agent_diamond = $this->saveImage($request->file('agent_diamond'),$old,'agent_diamond');
            $config['agent_diamond'] = $agent_diamond;
            $newImage= $agent_diamond;
            if(isset($old) and $old and $newImage) {
                Agent::where('icon',$old)->update(['icon' => $newImage]);
            }
        }

        if($request->file('agent_gold')) {
            $old = (isset($config['agent_gold'])) ? $config['agent_gold'] : null;
            $agent_gold = $this->saveImage($request->file('agent_gold'),$old,'agent_gold');
            $config['agent_gold'] = $agent_gold;
            $newImage= $agent_gold;
            if(isset($old) and $old and $newImage) {
                Agent::where('icon',$old)->update(['icon' => $newImage]);
            }
        }

        if($request->file('agent_silver')) {
            $old = (isset($config['agent_silver'])) ? $config['agent_silver'] : null;
            $agent_silver = $this->saveImage($request->file('agent_silver'),$old,'agent_silver');
            $config['agent_silver'] = $agent_silver;
            $newImage= $agent_silver;
            if(isset($old) and $old and $newImage) {
                Agent::where('icon',$old)->update(['icon' => $newImage]);
            }
        }

        if($request->file('agent_unclassified')) {
            $old = (isset($config['agent_unclassified'])) ? $config['agent_unclassified'] : null;
            $agent_unclassified = $this->saveImage($request->file('agent_unclassified'),$old,'agent_unclassified');
            $config['agent_unclassified'] = $agent_unclassified;
            $newImage= $agent_unclassified;
            if(isset($old) and $old and $newImage) {
                Agent::where('icon',$old)->update(['icon' => $newImage]);
            }
        }

        if($request->file('agent_rival')) {
            $old = (isset($config['agent_rival'])) ? $config['agent_rival'] : null;
            $agent_rival = $this->saveImage($request->file('agent_rival'),$old,'agent_rival');
            $config['agent_rival'] = $agent_rival;
            $newImage= $agent_rival;
            if(isset($old) and $old and $newImage) {
                Agent::where('icon',$old)->update(['icon' => $newImage]);
            }
        }


        file_put_contents(public_path().'/config/config.json', json_encode($config));
        return redirect()->route('Admin::config@index')
            ->with('success', 'Đã cập nhật thành công');
    }

    public function globalConfig(Request $request) {
        return view('admin.config.globalConfig');


    }

    public function postGlobalConfig(Request $request) {
        $data = $request->all();

        $textColor = $data['textColor'];
        $fontSize = $data['fontSize'];
        foreach (\App\Models\User::$positionTexts as $key => $value) {

            if(isset($textColor[$key]) and isset($fontSize[$key])) {
                if($textColor[$key] and $fontSize[$key]) {
                    $config = Config::firstOrCreate(['position_id'=> $key]);
                    $config->update([
                         'fontSize' => $fontSize[$key],
                        'textColor' =>$textColor[$key]
                    ]);

                }
            }
        }
        return redirect()->back()->with('success','Cập nhật thành công');
    }

    public function globalEnable(Request $request) {
        foreach (\App\Models\User::$positionTexts as $key => $value) {
            $config = Config::where('position_id',$key)->first();
            User::where('position',$key)->update([
                'textColor' => ($config and $config->textColor) ? $config->textColor : '#000000',
                'fontSize' => ($config and $config->fontSize) ? $config->fontSize : 12
            ]);

        }
        return redirect()->back()->with('success','Cập nhật thành công');
    }
}
