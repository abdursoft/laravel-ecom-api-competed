<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthHelper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        try {
            $token = JwtToken::verifyToken('token');
            $request->headers->set('email',$token->email);
            $request->headers->set('id',$token->id);
            return $next($request);
        } catch (\Throwable $th) {
            return redirect('/user/login')->with('error',$th->getMessage());
        }
    }
}
