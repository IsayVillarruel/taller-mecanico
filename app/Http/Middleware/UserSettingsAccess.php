<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class UserSettingsAccess
{
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::User()->hasPerm(1))
        { 
            abort(401);
        }
        return $next($request);
    }
}
