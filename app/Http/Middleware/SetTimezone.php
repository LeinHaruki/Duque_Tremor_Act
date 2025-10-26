<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get timezone from session or use default
        $timezone = session('timezone', config('app.timezone'));
        
        // Set the application timezone
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);
        
        return $next($request);
    }
}