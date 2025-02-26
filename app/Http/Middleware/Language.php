<?php

namespace App\Http\Middleware;

use Closure;

class Language
{
    public function handle($request, Closure $next)
    {
        $locale = 'en';
        if (optional(auth()->user())->language) {
            $locale = auth()->user()->language;
        } elseif (function_exists('get_settings')) {
            $locale = get_settings('language', true);
        }

        app()->setlocale(session('language', ($locale ? $locale : 'en')));

        return $next($request);
    }
}
