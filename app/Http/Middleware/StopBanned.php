<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Hash;


class StopBanned
{
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
            if (!$user['banned'])
                return $next($request);

            return response()->json([
                    "success" => false,
                    "message" => "Your account was banned"
                ], 403);
        }
            
        return response()->json([
                        "success" => false,
                        "message" => "Unauthorized"
                    ], 401);
    }
}
