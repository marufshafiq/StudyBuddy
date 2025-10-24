<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'student') {
            // Redirect teachers to teacher dashboard
            if (auth()->user()->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            // Redirect admins or others to main dashboard
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
