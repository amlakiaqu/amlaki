<?php

namespace App\Http\Middleware;

use Closure;


class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('admin/*')) {
            // Enforce the language for all admin APIs
            \App::setLocale('en');
        } else {
            // Set the local for the request depending on "Accept-Language" header.
            // If "Accept-Language" header doesn't exist, then the default language will be applied
            $lang = $request->header("Accept-Language", config('app.locale'));
            if ($lang && $lang != config('app.locale', 'en')) {
                if (in_array($lang, config('app.supported_languages', ['en']))) {
                    \App::setLocale($lang);
                } else {
                    \App::setlocale(config('app.locale', 'en'));
                }
            }
        }
        return $next($request);
    }
}