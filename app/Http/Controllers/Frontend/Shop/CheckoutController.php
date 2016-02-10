<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Order\Worker\OrderWorkerInterface;
use App\Droit\Shop\Payment\Repo\PaymentInterface;
use App\Events\OrderWasPlaced;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $pays;
    protected $canton;
    protected $profession;
    protected $checkout;
    protected $order;
    protected $payment;
    protected $adresse;

    public function __construct(
        UserInterface $user,
        CantonInterface $canton,
        PaysInterface $pays,
        ProfessionInterface $profession,
        CartWorkerInterface $checkout,
        OrderWorkerInterface $order,
        PaymentInterface $payment,
        AdresseInterface $adresse
    )
    {
        $this->user       = $user;
        $this->adresse    = $adresse;
        $this->pays       = $pays;
        $this->canton     = $canton;
        $this->profession = $profession;
        $this->checkout   = $checkout;
        $this->order      = $order;
        $this->payment    = $payment;
    }

    /**
     * Display cart
     *
     * @return Response
     */
    public function cart()
    {
        $user   = $this->user->find(\Auth::user()->id);
        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);
        $total  = $this->checkout->totalCart();

        return view('frontend.pubdroit.checkout.cart')->with(compact('user','coupon','total'));
    }

    /**
     * Display billing form
     *
     * @return Response
     */
    public function billing()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.checkout.billing')->with(compact('user'));
    }


    /**
     * Display checkout
     *
     * @return Response
     */
    public function resume(Request $request)
    {
        $data = $request->all();

        if(!empty($data))
        {
            if(isset($data['id']))
            {
                $this->adresse->update($data);
            }
            else{
                $this->adresse->create($data);
            }
        }

        $shipping  = $this->checkout->totalShipping();
        $total     = $this->checkout->totalCartWithShipping();
        $payments  = $this->payment->getAll();
        $user      = $this->user->find(\Auth::user()->id);
        $coupon    = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('frontend.pubdroit.checkout.resume')->with(compact('user','coupon','shipping','coupon','total','payments'));
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
        $payments  = $this->payment->getAll();

        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('frontend.pubdroit.checkout.confirm')->with(compact('user','shipping','coupon','total','payments'));
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

        $order    = $this->order->make($shipping,$coupon);

        $order->load('user');

        // Payment
        if($request->input('stripeToken'))
        {
            $charge = $this->viaStripe($request->input('stripeToken'),$order);

            $order->payed_at       = \Carbon\Carbon::now();
            $order->transaction_no = $charge->id;
            $order->status         = 'payed';
            $order->payement_id    = 2;
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
                    "amount"      => $order->total_with_shipping * 100, // amount in cents, again
                    "currency"    => "chf",
                    "source"      => $token,
                    "description" => $order->user->email
                )
            );

            return $charge;
        }
        catch(\Stripe\Error\Card $e)
        {
            throw new \App\Exceptions\CardDeclined('Carte décliné');
        }
    }

    public function cleanUp(){

        // Destroy cart
        \Cart::destroy();
        session()->forget('noShipping');
        session()->forget('coupon');

    }

}
