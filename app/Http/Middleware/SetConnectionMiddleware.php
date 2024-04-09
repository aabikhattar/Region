<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $country = request()->header('x-country');

        if($country == 'US'){
            config(['database.default' => env('DB_CONNECTION_US')]);
            request()->headers->set('database_connection', env('DB_CONNECTION_US'));
        }
        else if($country == 'RU'){
            config(['database.default' => env('DB_CONNECTION_RU')]);
            request()->headers->set('database_connection', env('DB_CONNECTION_RU'));
        }

        return $next($request);
    }
}
