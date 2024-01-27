<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);

//        if(Auth::check() && Auth::user()->role_id==1)
//        return back();

//        if(!Auth::check()){
//            return $next($request);
//        }
//        if(!in_array(auth()->user()->role_id, [1, 2]) && $request->is('admin')){
//            return redirect()->route('/')->with('success', trans('site.application_success'));
//        }

    }
}
