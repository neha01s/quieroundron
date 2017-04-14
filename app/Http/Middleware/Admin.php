<?php namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin {

    public function handle($request, Closure $next)
    {

        if ( Auth::check() && Auth::user()->user_role ==2 )
        {
            return $next($request);
        }

        return redirect()->guest('admin/login');

    }

}

