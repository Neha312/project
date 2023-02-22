<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module, $permission): Response
    {
        //check user type for route access
        if (auth()->user()->type == 'superadmin') {
            return $next($request);
        } else {
            $user =   Auth::user()->hasAccess($module, $permission);
            if ($user) {
                return $next($request);
            }
            return response()->json(['not access']);
        }
    }
}
