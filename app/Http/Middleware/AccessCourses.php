<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AccessCourses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $course = $request->route('course');

        if ($course->visibility === 'public') {
            return $next($request);
        }

        // Unlisted: Allow if user has the direct link
        if ($course->visibility === 'unlisted' && !$request->expectsJson()) {
            return $next($request);
        }

        if (Auth::check() && (
            $course->students()->where('user_id', Auth::id())->exists() ||
            $course->allowedUsers()->where('user_id', Auth::id())->exists()
        )) {
            return $next($request);
        }

        abort(403, 'You do not have access to this course.');
    }



}
