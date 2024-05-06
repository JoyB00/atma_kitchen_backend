<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleCheck
{
    protected $allowedRoles;

    public function __construct(...$allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array(auth()->user()->role_id,  $this->allowedRoles)) {
            return $next($request);
        }
        return response([
            'message' => 'Not Accessable' . auth()->user()->role_id . 'and' . $this->allowedRoles
        ], 400);
    }
}
