<?php
namespace App\Http\Middleware;

use Closure;

class RedirectIfEmployeeLoggedIn
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('employee_id')) {
            return redirect()->route('orders.index'); // or wherever logged-in users go
        }
        return $next($request);
    }
}
