<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Shop\Cart\Worker\CartWorker;
use App\Droit\Shop\Order\Worker\OrderWorker;

use App\Events\OrderWasPlaced;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $pays;
    protected $canton;
    protected $profession;
    protected $checkout;
    protected $order;

    public function __construct(UserInterface $user, CantonInterface $canton, PaysInterface $pays, ProfessionInterface $profession, CartWorker $checkout, OrderWorker $order)
    {
        $this->middleware('auth');
        $this->middleware('cart');

        $this->user       = $user;
        $this->pays       = $pays;
        $this->canton     = $canton;
        $this->profession = $profession;
        $this->checkout   = $checkout;
        $this->order      = $order;
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
        $user      = $this->user->find(\Auth::user()->id);
        $shipping  = $this->checkout->totalShipping();
        $total     = $this->checkout->totalCartWithShipping();

        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('shop.checkout.confirm')->with(compact('user','shipping','coupon','total'));
    }

    /**
     * Display checkout
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $coupon   = (\Session::has('coupon') ? \Session::get('coupon') : false);
        $shipping = $this->checkout->getTotalWeight()->setShipping()->orderShipping;

        $order = $this->order->make($shipping,$coupon);

        // Payement
        if($request->input('stripeToken'))
        {
            $this->viaStripe($request->input('stripeToken'),$order);
            $order->payed_at    = \Carbon\Carbon::now();
            $order->status      = 'payed';
            $order->payement_id = 2;
            $order->save();
        }

        $this->cleanUp();

        event(new OrderWasPlaced($order));

        return redirect('/')->with(['status' => 'success', 'message' => 'Votre commande a été envoyé!']);

    }

    public function viaStripe($token,$order){

        \Stripe\Stripe::setApiKey("sk_test_ryko0RINfRXTIq65ATCIAPAV");

        // Create the charge on Stripe's servers - this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                    "amount"      => $order->amount, // amount in cents, again
                    "currency"    => "chf",
                    "source"      => $token,
                    "description" => "Example charge")
            );
        }
        catch(\Stripe\Error\Card $e)
        {
            throw new \App\Exceptions\CardDeclined('Carte décliné', $e->getError() );
        }
    }

    public function cleanUp(){

        // Destroy cart
        \Cart::destroy();
        session()->forget('noShipping');
        session()->forget('coupon');

    }

}
