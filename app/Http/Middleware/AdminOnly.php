<?php

namespace App\Http\Middleware;
use App\User;
use Illuminate\Support\Facades\Hash;

use Closure;

class AdminOnly{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $user = User::where('email', $request['email'])->first();
        if ($user && Hash::check($request['password'], $user['password'])){
            if ($user['role']=='Admin')
                return $next($request);
            return response()->json([
                    "success" => false,
                    "message" => "Your are not admin"
                ], 403);
        }
            
        return response()->json([
                        "success" => false,
                        "message" => "Unauthorized"
                    ], 401);
    }
}
