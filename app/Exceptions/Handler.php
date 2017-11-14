<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
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
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
	    if ($e instanceof \App\Exceptions\CouponException){
			$request->session()->flash('wrongCoupon', $e->getMessage());
			return redirect()->back();
        }

		if($e instanceof \App\Exceptions\MissingException) {
			alert()->warning($e->getMessage());
			return redirect()->back();
		}
		
		if($e instanceof \App\Exceptions\FactureColloqueTestException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\ColloqueMissingInfoException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\ProductMissingInfoException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if($e instanceof \App\Exceptions\AccountValidationException)
		{
			alert()->warning($e->getMessage());
			return redirect()->back()->withInput();
		}

		if($e instanceof \App\Exceptions\AdresseRemoveException)
		{
			alert()->warning($e->getMessage());

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

		if ($e instanceof \App\Exceptions\NoDateReminder){
			alert()->danger('Aucune date indiqué pour le rappel');
			return redirect()->back();
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
			alert()->warning($e->getMessage());
			return redirect()->back();
		}

		if ($e instanceof \App\Exceptions\CampagneSendException) {
			alert()->warning($e->getMessage());
			return redirect()->back();
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
            $request->session()->flash('AdresseMissing', 'Ok');
			return redirect('/pubdroit/profil');
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

		if($e instanceof \Illuminate\Database\Eloquent\ProductNotFoundException){
			alert()->warning('Aucune livre trouvé pour abonnent');
			return redirect()->back();
		}

		if ($e instanceof \Illuminate\Session\TokenMismatchException)
			return redirect('login');
			
        return parent::render($request, $e);
    }
}
