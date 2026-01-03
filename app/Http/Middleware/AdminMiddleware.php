<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user->role || strtolower(trim($user->role->name)) !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực quản trị.');
        }

        return $next($request);
    }
}
