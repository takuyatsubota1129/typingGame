<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotInstantWinForm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('instantwin/form') && !$request->is('instantwin/select') && !$request->is('instantwin/selectTen') && !$request->is('instantwin/result')) {
            return redirect('instantwin/form');
        }

        return $next($request);
    }
}
