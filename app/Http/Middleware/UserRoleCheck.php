<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserRoleCheck
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (in_array(auth()->user()->role_id,  $roles)) {
            return $next($request);
        }
        return response([
            'message' => 'Not Accessable'
        ], 400);
    }
}
