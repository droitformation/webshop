<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * These middleware are run during every request to your application.
	 *
	 * @var array
	 */
	protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
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
	 * @var array
	 */
	protected $routeMiddleware = [

        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'honeypot' =>  \Spatie\Honeypot\ProtectAgainstSpam::class,
		'site'           => 'App\Http\Middleware\SiteMiddleware',
		'checkout'       => 'App\Http\Middleware\CheckoutMiddleware',
        'cart'           => 'App\Http\Middleware\CartMiddleware',
        'administration' =>'App\Http\Middleware\AdminMiddleware',
        'gestion'        =>'App\Http\Middleware\GestionMiddleware',
		'team'           =>'App\Http\Middleware\TeamMiddleware',
        'pending'        =>'App\Http\Middleware\PendingPayement',
        'registered'     =>'App\Http\Middleware\Registered',
		'strip'          =>'App\Http\Middleware\StripRequest',
		'abo'            =>'App\Http\Middleware\OrderAbo',
		'already'        =>'App\Http\Middleware\AlreadyRegistered',
		'back'           =>'App\Http\Middleware\BackMiddleware',
        'account'        =>'App\Http\Middleware\AccountUpdateMiddleware',
        'access'         =>'App\Http\Middleware\AccessUserMiddleware',
        'impostor'         =>'App\Http\Middleware\BanImpostor',
        'clean'         =>'App\Http\Middleware\CleanMiddleware',
	];

}
