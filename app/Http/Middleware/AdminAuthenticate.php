<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class AdminAuthenticate
{

    public function handle($request, Closure $next)
    {
        //login check

        if (auth()->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/login');
            }
        }
        if(Auth::user()->lang){
            $locale = Auth::user()->lang;
            \App::setLocale($locale);
        }

        return $next($request);
    }
}
