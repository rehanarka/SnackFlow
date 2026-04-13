<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleLogin
{
public function handle($request, Closure $next)
{
    if (auth()->user()->role !== 'admin') {
        return redirect('/login');
    }
    return $next($request);
}
}
