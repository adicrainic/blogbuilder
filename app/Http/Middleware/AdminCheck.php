<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCheck
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
        if(!Auth::check()){
            return response()->json([
                'msg' => 'You are not logged in to access this !'
            ]);
        }
        $user = Auth::user();
        if($user->userType == 'User') {
            return response()->json([
                'msg' => 'You are not allowed to access this !'
            ]);
        }
        return $next($request);
    }
}
