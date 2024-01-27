<?php

namespace App\Http\Middleware;

use App\Enums\ApplicationStatusEnum;
use Closure;
use Illuminate\Http\Request;

class CloneMiddleware
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
        if(($request->application->user_id === auth()->user()->id && $request->application->status === ApplicationStatusEnum::Canceled ) || ($request->application->user_id === auth()->user()->id && $request->application->status === ApplicationStatusEnum::Refused) || ($request->application->user_id === auth()->user()->id && $request->application->status === ApplicationStatusEnum::Rejected))
        {
            return $next($request);
        }
        abort(403,'Unauthorized action.');
    }
}
