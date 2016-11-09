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
        if ($e instanceof \App\Exceptions\CouponException){
			$request->session()->flash('wrongCoupon', $e->getMessage());
			return redirect()->back();
        }

		if($e instanceof \App\Exceptions\FactureColloqueTestException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\OrderAboException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

        if ($e instanceof \App\Exceptions\CardDeclined){
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\InscriptionExistException){
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\OrderCreationException){
			alert()->danger($e->getMessage());
			return redirect('checkout/confirm');
		}

		if ($e instanceof \App\Exceptions\RegisterException){
			alert()->danger('Vous êtes déjà inscrit à ce colloque');
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\CampagneCreationException){
			alert()->warning('Problème avec la création de campagne sur mailjet');
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\ContentCreationException){
			alert()->warning('Problème avec la création du contenu pour la campagne');
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\FileUploadException){
			alert()->warning('Problème avec le téléchargement du fichier '.$e->getMessage());
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\SubscribeUserException){
			alert()->warning('Erreur synchronisation email vers mailjet');
			return redirect('/');
		}

		if ($e instanceof \App\Exceptions\CampagneSendException){
			alert()->warning('Erreur avec l\'envoi de la newsletter, mailjet à renvoyé une erreur');
			return redirect('/');
		}

		if ($e instanceof \App\Exceptions\DeleteUserException){
			alert()->warning('Erreur avec la suppression de l\'abonnés sur mailjet');
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\UserNotExistException){
			alert()->warning('Cet utilisateur n\'existe pas');
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\StockCartException){
			alert()->warning('Il n\'y a plus assez de stock pour cet article');
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\ColloqueCompteException){
			alert()->warning('Attention! Le colloque n\'as pas de compte! Veuillez contrôler le compte attaché au colloque.');
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\AdresseNotExistException){
			alert()->warning('Il n\'existe aucune adresse de livraison, veuillez indiquer une adresse valide dans');
			return redirect('/')->with(['link' => 'profil']);
		}

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
            alert()->warning('Méthode non autorisée');
            return redirect()->back();
        }

		if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
			return response()->view('404', [], 404);
		}
		
		if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
            alert()->warning('Aucune données trouvé');
            return redirect()->back();
		}

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