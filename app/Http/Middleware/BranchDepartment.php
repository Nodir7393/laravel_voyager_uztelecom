<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BranchDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->branch_id != null && $user->departmen_id != null)
        {
            return $next($request);
        }else{
            if($user->department_id == null)
            {
                abort(405,"Вы не выбрали ваш Отдел, Админ должен перенаправить вас в отдел");
            }elseif($user->branch_id == null){
                abort(405,"Вы не выбрали ваш Филиал, Админ должен перенаправить вас в филиал");
            }
        }
        return $next($request);

    }
}
