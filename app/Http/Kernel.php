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
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
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
			\LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware::class,
		],

		'api' => [
			'throttle:60,1',
		],
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
		//'beta' =>'App\Http\Middleware\Beta',
	];

}
