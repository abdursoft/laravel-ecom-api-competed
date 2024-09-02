<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthHelper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = JwtToken::verifyToken('token');
        if($token){
            $token = JwtToken::verifyToken('token');
            $request->headers->set('email',$token->email);
            $request->headers->set('id',$token->id);
            return $next($request);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized',
            ],401);
        }
    }
}
