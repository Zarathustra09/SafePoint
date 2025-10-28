<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRestricted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_restricted) {
            // Add a warning to the session that will be displayed to the user
            if (!$request->session()->has('restriction_warning_shown')) {
                $request->session()->flash('warning', 'Your account is currently restricted. Some features may be limited.');
                $request->session()->put('restriction_warning_shown', true);
            }
        }

        return $next($request);
    }
}
