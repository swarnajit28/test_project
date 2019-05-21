<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
         //    return redirect('/home');
      /*     if (Auth::user()->user_type == 'IT') {
                return redirect()->route('itmanager-dashboard');
            }  if (Auth::user()->user_type == 'MA') {
                return redirect()->route('management-dashboard');
            } else if (Auth::user()->user_type == 'LM') {
                return redirect()->route('lead-manager-dashboard');
            } else if (Auth::user()->user_type == 'SP') {
                return redirect()->route('sales-persons-dashboard');
            } else {
                return redirect()->route('home');
            } */
            if (Auth::user()) {
                    return redirect()->route('user-type-dashboard');
                } else {
                    return view('auth.login');
                }
        }

        return $next($request);
    }

}
