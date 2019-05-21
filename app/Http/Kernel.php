<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
       \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'MA.SM'       => \App\Http\Middleware\SimplifyMA_SMAccess::class,
        'MA'       => \App\Http\Middleware\SimplifyMA_Access::class,
        'IT'       => \App\Http\Middleware\SimplifITManagerAccess::class,
        'LM'       => \App\Http\Middleware\SimplifLeadManagerAccess::class,
        'SP'       => \App\Http\Middleware\SimplifSalePersonAccess::class,
        'MA.LM.SM'  => \App\Http\Middleware\SimplifyMA_LM_SMaccess::class,
        'MA.LM.SP.SM.OM'  => \App\Http\Middleware\SimplifyLM_MA_SP_SM_OMAccess::class,
        'MA.LM.SP.SM'  => \App\Http\Middleware\SimplifyLM_MA_SP_SMAccess::class,
        'MA.SP.SM.OM'  => \App\Http\Middleware\SimplifyMA_SP_SM_OMaccess::class,
        'MA.SP'       => \App\Http\Middleware\SimplifyMA_SPaccess::class,
        'SM.OM'       => \App\Http\Middleware\SimplifySM_OMaccess::class,
    ];
}
