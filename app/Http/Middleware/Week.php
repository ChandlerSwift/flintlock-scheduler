<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Week
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! \App\Models\Week::find($request->cookie('week_id'))) {
            return redirect('/weeks');
        }

        return $next($request);
    }
}
