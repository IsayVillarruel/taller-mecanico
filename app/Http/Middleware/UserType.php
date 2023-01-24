<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class UserType
{
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->isAdmin(1))
        {
            return $next($request);
        }
        
        return abort(404, 'La pagina a la cual quieres acceder no esta disponible!');
    }
}
