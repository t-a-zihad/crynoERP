<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmployeeSession
{
    public function handle(Request $request, Closure $next)
    {
        $employeeId = session('employee_id');

        $loginRoute = route('login'); // Adjust route name as per your login route

        // If user is not logged in and not requesting login page, redirect to login
        if (!$employeeId && !$request->is('login')) {
            return redirect($loginRoute)->with('error', 'Please login first.');
        }

        // If user is logged in and trying to access login page, redirect to dashboard or home
        if ($employeeId && $request->is('login')) {
            return redirect()->route('orders.index'); // Or any dashboard/home route
        }

        return $next($request);
    }
}
