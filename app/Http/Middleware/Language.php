<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;

class Language  {

    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $raw_locale = \Session::get('locale');
        if (in_array($raw_locale, \Config::get('app.locales'))) {
            $locale = $raw_locale;
        } else{
            $locale = \Config::get('app.locale');
        }
        \App::setLocale($locale);
        return $next($request);
    }

}