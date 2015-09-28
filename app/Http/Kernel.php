<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
        'LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware'
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
        'csrf' => 'App\Http\Middleware\VerifyCsrfToken',
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'cart' => 'App\Http\Middleware\CartMiddleware',
        'oauth' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware',
        'oauth-owner' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthOwnerMiddleware',
        'check-authorization-params' => 'LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware',
        'administration' =>'App\Http\Middleware\AdminMiddleware',
        'pending' =>'App\Http\Middleware\PendingPayement',
        'registered' =>'App\Http\Middleware\Registered',
	];

}
