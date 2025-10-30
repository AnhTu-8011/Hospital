<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Temporary: Allow specific user ID during development
        if (in_array(Auth::id(), [1])) {  // Replace 1 with your user ID
            return $next($request);
        }

        $user = Auth::user();
        if (!$user->role || strtolower(trim($user->role->name)) !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực quản trị.');
        }

        return $next($request);
    }
}
