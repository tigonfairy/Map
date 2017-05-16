<?php

namespace App\Http\Middleware;

use Closure;

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


        return $next($request);
    }
}
