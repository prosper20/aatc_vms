<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle(Request $request, Closure $next)
    {
        // if (Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }

        \Log::info('✅ Localization middleware hit');

        if (\Session::has('locale')) {
            \Log::info('✅ Locale in session: ' . \Session::get('locale'));
            \App::setLocale(\Session::get('locale'));
        } else {
            \Log::info('❌ No locale in session');
        }
        return $next($request);
    }
}
