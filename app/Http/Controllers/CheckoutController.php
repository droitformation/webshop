<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Civilite\Repo\CiviliteInterface;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $pays;
    protected $civilite;

    public function __construct(UserInterface $user, CiviliteInterface $civilite, PaysInterface $pays)
    {
        $this->user     = $user;
        $this->pays     = $pays;
        $this->civilite = $civilite;
    }

    /**
	 * Display checkout
	 *
	 * @return Response
	 */
	public function resume()
	{
        $user = $this->user->find(\Auth::user()->id);

        return view('shop.checkout.resume')->with(compact('user'));
	}

    /**
     * Display checkout
     *
     * @return Response
     */
    public function billing()
    {
        $user = $this->user->find(\Auth::user()->id);

        $civilite = $this->civilite->getAll();
        $pays     = $this->pays->getAll();

        return view('shop.checkout.billing')->with(compact('user','pays','civilite'));
    }

}
