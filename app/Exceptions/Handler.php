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
        if ($e instanceof \Illuminate\Contracts\Encryption\DecryptException){
            dd($e->getMessage());
        }

        if ($e instanceof \App\Exceptions\CouponException){
			$request->session()->flash('wrongCoupon', $e->getMessage());
			return redirect()->back();
        }

		if($e instanceof \App\Exceptions\MissingException) {
            flash($e->getMessage())->warning();
			return redirect()->back();
		}
		
		if($e instanceof \App\Exceptions\FactureColloqueTestException)
		{
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\ColloqueMissingInfoException)
		{
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\ProductMissingInfoException)
		{
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\AccountValidationException)
		{
		    flash($e->getMessage())->warning();
			return redirect()->back()->withInput();
		}

		if($e instanceof \App\Exceptions\AdresseRemoveException)
		{
		    flash($e->getMessage())->warning();

			$back = redirect()->getUrlGenerator()->previous();

			$uri      = explode('/',$back);
			$fragment = isset($uri[1]) ? $uri[1] : null;

			if($fragment == 'users' && $fragment == 'user'){
				session()->keep('user_search');
			}

			if($fragment == 'adresses' && $fragment == 'adresse'){
				session()->keep('adresse_search');
			}

			if($back == url('admin/users') || $back == url('admin/adresses')){
				$back = $back.'/back';
			}

			return redirect($back);
		}

		if($e instanceof \App\Exceptions\OrderAboException)
		{
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

        if ($e instanceof \App\Exceptions\CardDeclined){
            flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\InscriptionExistException){
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

        if ($e instanceof \App\Exceptions\BadFormatException){
            flash($e->getMessage())->warning();
            return redirect()->back();
        }

		if ($e instanceof \App\Exceptions\OrderCreationException){
            flash($e->getMessage())->error();
			return redirect('checkout/confirm');
		}

		if ($e instanceof \App\Exceptions\NoDateReminder){
            flash('Aucune date indiqué pour le rappel')->error();
			return redirect()->back();
		}

        if ($e instanceof \App\Exceptions\EmailSubstituteException){
            flash('L\'email '.$e->getMessage().' est un email de substitution et n\'est pas valide pour l\'envoi de confirmation.')->error();
            return redirect()->back();
        }

		if ($e instanceof \App\Exceptions\RegisterException){
            flash('Vous êtes déjà inscrit à ce colloque')->error();

            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\CampagneCreationException){
            flash('Problème avec la création de campagne sur mailjet, re-essayer et si le problème persiste avertir le webmaster.')->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\ContentCreationException){
            flash('Problème avec la création du contenu pour la campagne')->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\FileUploadException){
            flash('Problème avec le téléchargement du fichier '.$e->getMessage())->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\SubscribeUserException){
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\CampagneSendException) {
		    flash($e->getMessage())->warning();
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\DeleteUserException){
            flash('Erreur avec la suppression de l\'abonnés sur mailjet')->warning();
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\UserNotExistException){
            flash('Cet utilisateur n\'existe pas')->warning();
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\StockCartException){
            flash('Il n\'y a plus assez de stock pour cet article')->warning();
            return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\ColloqueCompteException){
            flash('Attention! Le colloque n\'as pas de compte! Veuillez contrôler le compte attaché au colloque.')->warning();
			return redirect()->back();
		}

        if ($e instanceof \App\Exceptions\AdresseTypeException){
            flash('Attention! Un compte doit avoir une adresse de contact!')->warning();
            return redirect()->back();
        }

		if ($e instanceof \App\Exceptions\AdresseNotExistException){
            flash('Il n\'existe aucune adresse de livraison, veuillez indiquer une adresse valide dans')->warning();
			return redirect('/')->with(['link' => 'profil']);
		}

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
            flash('Méthode non autorisée')->warning();
            return redirect()->back();
        }

		if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
		    \Log::info('previous '.url()->previous());
			return response()->view('404', [], 404);
		}
		
		if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
            flash('Aucune données trouvé')->warning();
            return redirect()->back();
		}

		if($e instanceof \Illuminate\Database\Eloquent\ProductNotFoundException){
            flash('Aucune livre trouvé pour abonnent')->warning();
			return redirect()->back();
		}

        if ($e instanceof \Illuminate\Session\TokenMismatchException)
        {
            $request->session()->flash('sessionExpired', 'Ok');

            return redirect()->back()->withInput();
        }

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