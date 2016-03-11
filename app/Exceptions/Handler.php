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

        if ($e instanceof \App\Exceptions\CampagneCreationException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec la création de campagne sur mailjet'));

        if ($e instanceof \App\Exceptions\ContentCreationException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec la création du contenu pour la campagne'));

        if ($e instanceof \App\Exceptions\FileUploadException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Problème avec l\'upload '.$e->getMessage() ));

        if ($e instanceof \App\Exceptions\SubscribeUserException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur synchronisation email vers mailjet'));

        if ($e instanceof \App\Exceptions\CampagneSendException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur avec l\'envoi de la newsletter, mailjet à renvoyé une erreur'));

        if ($e instanceof \App\Exceptions\DeleteUserException)
            return redirect('/')->with(array('status' => 'warning' , 'message' => 'Erreur avec la suppression de l\'abonnés sur mailjet'));

        if ($e instanceof \App\Exceptions\UserNotExistException)
            return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Cet utilisateur n\'existe pas'));

		if ($e instanceof \App\Exceptions\AdresseNotExistException)
			return redirect()->back()->with(array('status' => 'warning' , 'message' => 'Aucune adresse pour cet utilisateur'));

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException)
            return redirect()->to('admin');

		if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
		{
			return response()->view('404', [], 404);
		}

		return parent::render($request, $e);
	}

}
