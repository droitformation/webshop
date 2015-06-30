<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Shop\Cart\Worker\CartWorker;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $pays;
    protected $canton;
    protected $profession;
    protected $checkout;

    public function __construct(UserInterface $user, CantonInterface $canton, PaysInterface $pays, ProfessionInterface $profession, CartWorker $checkout)
    {
        $this->middleware('auth');
        $this->middleware('cart');

        $this->user       = $user;
        $this->pays       = $pays;
        $this->canton     = $canton;
        $this->profession = $profession;
        $this->checkout   = $checkout;
    }

    /**
	 * Display checkout
	 *
	 * @return Response
	 */
	public function resume()
	{
        $cantons     = $this->canton->getAll();
        $professions = $this->profession->getAll();
        $pays        = $this->pays->getAll();

        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

        $user = $this->user->find(\Auth::user()->id);

        return view('shop.checkout.resume')->with(compact('user','pays','cantons','professions','coupon'));
	}

    /**
     * Display checkout
     *
     * @return Response
     */
    public function confirm()
    {
        $user     = $this->user->find(\Auth::user()->id);
        $shipping = $this->checkout->totalShipping();
        $total    = $this->checkout->totalCartWithShipping();

        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('shop.checkout.confirm')->with(compact('user','shipping','coupon','total'));
    }

    /**
     * Display checkout
     *
     * @return Response
     */
    public function send()
    {
        $user     = $this->user->find(\Auth::user()->id);
        $shipping = $this->checkout->totalShipping();
        $total    = $this->checkout->totalCartWithShipping();

        $coupon   = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('shop.index')->with(['status' => 'success', 'message' => 'Votre commande a été envoyé!']);
    }

}
