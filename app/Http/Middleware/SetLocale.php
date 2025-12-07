<?php

namespace App\Http\Middleware;

use App\Models\SiteSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = SiteSettings::instance()->locale ?? config('app.locale', 'fr');

        App::setLocale($locale);

        return $next($request);
    }
}
