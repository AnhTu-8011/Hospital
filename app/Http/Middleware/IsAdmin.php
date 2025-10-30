<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra user đã đăng nhập và có role_id = 3 (Admin)
        if (auth()->check() && auth()->user()->role_id == 3) {
            return $next($request);
        }

        // Nếu không phải admin → chuyển hướng
        return redirect('/')->with('error', 'Bạn không có quyền truy cập vào trang này!');
    }
}
