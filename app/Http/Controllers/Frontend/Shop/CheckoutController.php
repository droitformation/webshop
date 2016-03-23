<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Order\Worker\OrderMakerInterface; // new implementation

use App\Droit\Shop\Payment\Repo\PaymentInterface;
use App\Events\OrderWasPlaced;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $checkout;
    protected $order;
    protected $payment;
    protected $adresse;

    public function __construct(CartWorkerInterface $checkout, OrderMakerInterface $order, PaymentInterface $payment, AdresseInterface $adresse)
    {
        $this->adresse    = $adresse;
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
        $user   = \Auth::user();
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
        return view('frontend.pubdroit.checkout.billing')->with(['user' => \Auth::user()]);
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
        $user      = \Auth::user();
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
        $user      = \Auth::user();
        $shipping  = $this->checkout->totalShipping();
        $total     = $this->checkout->totalCartWithShipping();
        $payments  = $this->payment->getAll();

        $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

        return view('frontend.pubdroit.checkout.confirm')->with(compact('user','shipping','coupon','total','payments'));
    }

    /**
     * Place order
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $coupon   = (\Session::has('coupon') ? \Session::get('coupon') : false);
        $shipping = $this->checkout->getTotalWeight()->setShipping()->orderShipping;

        $order    = $this->order->make($request->all(),$shipping,$coupon);

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

    /**
     * Pay via stripe API
     */
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

    /*
     * Destroy cart
     * */
    public function cleanUp()
    {
        \Cart::destroy();
        session()->forget('noShipping');
        session()->forget('coupon');
    }

}
