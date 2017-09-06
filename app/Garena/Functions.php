<?php

/*
 * Logic Functions
 * All Site function must be put in here for easy control
 */
namespace App\Garena;

use App\Account;

class Functions
{
    /**
     * Hard code function using for testing.
     * We also must comment in app\Http\Kernel.php
     * about \App\Http\Middleware\VerifyCsrfToken
     * @param null $uid
     */
    public static function hardLogin($uid = null)
   {
       if (!$uid) {
           $uid = random_int(1, 111111);
       }

       $accounts = Account::firstOrCreate([
           'uid' => $uid
       ], [
           'username' => md5($uid),
           'email' => ''
       ]);

       auth('frontend')->login($accounts, true);
   }
    public static function calculateSaleReal($type,$id_manager ,$id,$startMonth = null,$endMonth = null) {
        $sltt = 0;
        if($type == 1) {
            $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)->where('product_id',$id)
                ->where('agent_id',$id_manager)
                ->get()->sum('sales_real');
        }
        if($type == 2) {
            $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gsv',$id_manager)->where('product_id',$id)
                ->get()->sum('sales_real');
        }
        if($type == 3) {
            $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.tv',$id_manager)->where('product_id',$id)
                ->get()->sum('sales_real');
        }
        if($type == 4) {
            $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.gdv',$id_manager)->where('product_id',$id)
                ->get()->sum('sales_real');
        }

        if($type == 5) {
            $sltt =  \App\Models\SaleAgent::where('month','>=',$startMonth)->where('month','<=',$endMonth)
                ->join('agents','agents.id', '=' ,'sale_agents.agent_id')->where('agents.manager_id',$id_manager)->where('product_id',$id)
                ->get()->sum('sales_real');
        }
       
        return $sltt;

    }
}