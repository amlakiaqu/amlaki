<?php

namespace App\Http\Middleware;

use Log;
use Closure;


class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->is('admin/*')){
            \App::setLocale('en');
        }
        return $next($request);
    }
}
