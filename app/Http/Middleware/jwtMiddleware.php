<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class jwtMiddleware
{
    
    public function handle(Request $request, Closure $next, $guard = null)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();

            if(!Auth::guard($guard)->check())
                return response()->json(['error' => 'Guard mismatch'], 403);
            auth()->shouldUse($guard);


            // return response()->json(['1' => Auth::guard($guard)->check(),
            //                          '2' => Auth::getDefaultDriver(),
            //                          '3' => $guard,
            //                  ], 403);

            

        }catch(Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return response()->json(['status' => 'Token is not found']);
            }

        }
        return $next($request);
    }
}
