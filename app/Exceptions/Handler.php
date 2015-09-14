<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
        \Log::error($e);

		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
        if ($e instanceof \App\Exceptions\CouponException)
            return \Redirect::back()->with(array('status' => 'warning' , 'message' => $e->getMessage()));

        if ($e instanceof \App\Exceptions\CardDeclined)
            return \Redirect::back()->with(array('status' => 'warning' , 'message' => $e->getMessage()));

        if ($e instanceof \App\Exceptions\OrderCreationException)
            return \Redirect::to('checkout/confirm')->with(array('status' => 'danger' , 'message' => $e->getMessage()));

        if ($e instanceof \App\Exceptions\RegisterException)
            return \Redirect::back()->with(array('status' => 'warning' , 'message' => 'Vous êtes déjà inscrit à ce colloque'));

		return parent::render($request, $e);
	}

}
