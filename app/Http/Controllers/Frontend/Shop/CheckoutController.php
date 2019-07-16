<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Order\Worker\OrderMakerInterface; // new implementation
use App\Droit\Abo\Worker\AboWorkerInterface;

use App\Droit\Shop\Payment\Repo\PaymentInterface;
use App\Events\OrderWasPlaced;
use App\Events\NewAboRequest;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    protected $user;
    protected $checkout;
    protected $order;
    protected $abo;
    protected $payment;
    protected $adresse;

    public function __construct(CartWorkerInterface $checkout, OrderMakerInterface $order, AboWorkerInterface $abo, PaymentInterface $payment, AdresseInterface $adresse)
    {
        $this->adresse  = $adresse;
        $this->checkout = $checkout;
        $this->order    = $order;
        $this->abo      = $abo;
        $this->payment  = $payment;

        $this->middleware('abo', ['only' => ['billing']]);
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
     * Display contact form
     *
     * @return Response
     */
    public function contact()
    {
        return view('frontend.pubdroit.checkout.contact')->with(['user' => \Auth::user()]);
    }

    /**
     * Display billing form
     *
     * @return Response
     */
    public function billing(Request $request)
    {
        if(!empty($request->except('_token'))){
            $this->adresse->contact($request->except('_token'));
        }

        return view('frontend.pubdroit.checkout.billing')->with(['user' => \Auth::user()]);
    }

    /**
     * Display checkout
     *
     * @return Response
     */
    public function resume(Request $request)
    {
        if(!empty($request->except('_token'))){
            //$this->adresse->facturation($request->except('_token'));

            session()->put('reference_no', $request->input('reference_no',null));
            session()->put('transaction_no', $request->input('transaction_no',null));
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
        if($this->checkout->orderShop())
        {
            $coupon   = (\Session::has('coupon') ? \Session::get('coupon') : false);
            $shipping = $this->checkout->getTotalWeight()->setShipping()->orderShipping;
            $order    = $this->order->make($request->all(),$shipping,$coupon);

            event(new OrderWasPlaced($order));
        }
        
        // Prepare a new abonnement for the client
        if($this->checkout->orderAbo())
        {
            $data = $this->checkout->getAboData();
            $abos = $this->abo->makeAbonnement($data);

            event(new NewAboRequest($abos, \Auth::user()));
        }

        $this->cleanUp();

        $request->session()->flash('OrderConfirmation', 'Ok');
        
        return redirect('pubdroit');
    }

    /*
     * Destroy cart
     * */
    public function cleanUp()
    {
        \Cart::instance('shop')->destroy();
        \Cart::instance('abonnement')->destroy();
        
        session()->forget('noShipping');
        session()->forget('coupon');
        session()->forget('reference_no');
        session()->forget('transaction_no');
    }

}
