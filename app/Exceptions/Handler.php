<?php namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
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

        if ($e instanceof \App\Exceptions\CampagneCreationException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec la création de campagne sur mailjet'));

        if ($e instanceof \App\Exceptions\ContentCreationException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec la création du contenu pour la campagne'));

        if ($e instanceof \App\Exceptions\FileUploadException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec le téléchargement du fichier '.$e->getMessage() ));

        if ($e instanceof \App\Exceptions\SubscribeUserException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur synchronisation email vers mailjet'));

        if ($e instanceof \App\Exceptions\CampagneSendException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur avec l\'envoi de la newsletter, mailjet à renvoyé une erreur'));

        if ($e instanceof \App\Exceptions\DeleteUserException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur avec la suppression de l\'abonnés sur mailjet'));

        if ($e instanceof \App\Exceptions\UserNotExistException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Cet utilisateur n\'existe pas'));

		if ($e instanceof \App\Exceptions\StockCartException)
			return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Il n\'y a plus assez de stock pour cet article'));

		if ($e instanceof \App\Exceptions\AdresseNotExistException)
			return redirect()
				->back()
				->with(['status' => 'warning' , 'message' => 'Il n\'existe aucune adresse de livraison, veuillez indiquer une adresse valide dans ', 'link' => 'profil']);

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException)
            return redirect()->to('admin');

		if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
			return response()->view('404', [], 404);

		if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException)
			return response()->view('404', [], 404);

		if ($e instanceof \Illuminate\Session\TokenMismatchException)
			return redirect('login');

		return parent::render($request, $e);
	}

	/**
	 * Convert an authentication exception into an unauthenticated response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Auth\AuthenticationException  $exception
	 * @return \Illuminate\Http\Response
	 */
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}

		return redirect()->guest('login');
	}
}