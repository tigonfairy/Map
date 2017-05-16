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
}