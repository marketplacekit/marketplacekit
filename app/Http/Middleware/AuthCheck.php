<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthCheck extends \Illuminate\Auth\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
       {

           $user = $request->user();
           if(!$user) {
               $request->session()->flash('warning', 'Please login to perform this action');
           }
           $response = $next($request);
           if(!$user) {

               $response->headers->set('X-IC-Redirect', '/login');
               return $response;
           }

           return $response;
       }

}
