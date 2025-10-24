<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'teacher') {
            // Redirect students to student dashboard
            if (auth()->user()->role === 'student') {
                return redirect()->route('student.dashboard');
            }
            // Redirect admins or others to main dashboard
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
