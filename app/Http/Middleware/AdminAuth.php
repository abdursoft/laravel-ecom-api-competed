<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthHelper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = JwtToken::verifyToken('admin');
        if($token){
            $token = JwtToken::verifyToken('admin');
            $request->headers->set('admin_email',$token->email);
            $request->headers->set('admin_id',$token->id);
            return $next($request);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized',
            ],401);
        }
    }
}
