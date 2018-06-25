<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Auth;

class isAdmin extends \Illuminate\Auth\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ( auth()->check() && auth()->user()->is_admin )
        {

        } else {
            return redirect()->to('/')->withError('Permission Denied');
        }

        return $next($request);
    }

}
