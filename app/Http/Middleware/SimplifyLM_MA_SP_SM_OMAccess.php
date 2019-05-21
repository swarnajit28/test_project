<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SimplifyLM_MA_SP_SM_OMAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
     public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::user()->user_type != 'MA'  &&  Auth::user()->user_type != 'LM'  &&  Auth::user()->user_type != 'SP'&&  Auth::user()->user_type != 'SM' &&  Auth::user()->user_type != 'OM' ) {
            return redirect('/authentication-error');
        }

        return $next($request);
    }
}
