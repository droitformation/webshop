<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Coupon\Repo\CouponInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Droit\Shop\Order\Worker\OrderMakerInterface; // new implementation

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class OrderController extends Controller {

	protected $product;
    protected $coupon;
    protected $categorie;
    protected $order;
    protected $pdfgenerator;
    protected $worker;
    protected $adresse;
    protected $shipping;
    protected $helper;

    protected $ordermaker;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
        ProductInterface $product,
        CouponInterface $coupon,
        CategorieInterface $categorie,
        OrderInterface $order,
        AdresseInterface $adresse,
        ShippingInterface $shipping,
        PdfGeneratorInterface $pdfgenerator,
        OrderMakerInterface $ordermaker
    )
	{
        $this->product       = $product;
        $this->coupon        = $coupon;
        $this->categorie     = $categorie;
        $this->order         = $order;
        $this->adresse       = $adresse;
        $this->shipping      = $shipping;
        $this->pdfgenerator  = $pdfgenerator;
        $this->ordermaker    = $ordermaker;

        $this->helper = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        view()->share('status_list',['pending' => 'En attente', 'payed' => 'Payé', 'gratuit' => 'Gratuit']);
        view()->share('send_list',['pending' => 'En attente', 'payed' => 'Envoyé']);
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request, $back = null)
	{
        // Defaults
        $data = ['period' => ['start' => \Carbon\Carbon::now()->startOfMonth()->toDateString(), 'end' => \Carbon\Carbon::now()->endOfMonth()->toDateString()]];

        if($back){
            $session = session()->has('order_search') ? session()->get('order_search') : [];
            $data = array_merge($data,$session);
        }
        else{
            $data = array_filter(array_merge($data,$request->except('_token')));
            session(['order_search' => $data]);
        }

        $orders = isset($data['order_no']) ? $this->order->search($data['order_no']) : $this->order->getPeriod($data);


        $cancelled = $this->order->getTrashed($data['period']);

        // filter invalid order beacuse no user adresse
        list($orders, $invalid) = $orders->partition(function ($order) {
            return $order->order_adresse;
        });

        if(isset($data['export'])) {
            if($orders->isEmpty()){ flash('Aucune commande à exporter')->warning();}

            $title = 'Commandes du '.$this->helper->betweenTwoDates($data['period']['start'],$data['period']['end']);

            return \Excel::download(new \App\Exports\OrderExport($orders,$data['columns'] ?? null ,$title, $data['details'] ?? null), 'commandes' . date('dmy').'.xlsx');
        }

        $request->flash();

		return view('backend.orders.index')
            ->with(['orders' => $orders, 'invalid' => $invalid, 'period' => $data['period'], 'columns' => config('columns.names'), 'cancelled' => $cancelled] + $data);
	}

    /**
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $shippings = $this->shipping->getAll();
        $order     = $this->order->find($id);
        $coupons   = $this->coupon->getValid();

        return view('backend.orders.show')->with(['order' => $order,'shippings' => $shippings, 'coupons' => $coupons]);
    }

    /**
     * Generate the invoice
     * if we changed something in user infos or product we can remake the invoice
     * Or if something went wrong the first time
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $order = $this->order->find($request->input('id'));

        $this->pdfgenerator->factureOrder($order);

        return ['link' => $order->facture];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products  = $this->product->listForAdminOrder();
        $shippings = $this->shipping->getAll();

        return view('backend.orders.create')->with(['products' => $products, 'shippings' => $shippings]);
    }

    public function verification(Request $request)
    {
        $preview = new \App\Droit\Shop\Order\Entities\OrderPreview($request->all());

        session()->put('reference_no', $request->input('reference_no',null));
        session()->put('transaction_no', $request->input('transaction_no',null));

        return view('backend.orders.verification')->with(['data' => $request->all(), 'preview' => $preview]);
    }

    public function correction(Request $request)
    {
        $data    = json_decode($request->input('data'),true);
        $preview = new \App\Droit\Shop\Order\Entities\OrderPreview($data);

        $adresse = $data['adresse'];
        $user_id = isset($data['user_id']) ? $data['user_id'] : null;

        if($user_id){
            unset($adresse['canton_id'],$adresse['pays_id'],$adresse['civilite_id']);
            $adresse = (isset($adresse) ? array_filter(array_values($adresse)) : []);
        }

        $data = [
            'old_products'   => $preview->products(true)->toArray(),
            'user_id'        => $user_id,
            'shipping_id'    => $data['shipping_id'],
            'tva'            => $data['tva'],
            'comment'        => isset($data['comment']) ? $data['comment'] : null,
            'paquet'         => isset($data['paquet']) ? $data['paquet'] : null,
            'free'           => isset($data['free']) ? 1 : null,
            'adresse'        => $adresse
        ];

        return redirect('admin/order/create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->input('data'),true);

        $shipping = var_exist($data,'shipping_id') ? $this->shipping->find($data['shipping_id']) : null;
        $shipping = var_exist($data,'free') ? null : $shipping;

        $order = $this->ordermaker->make($data,$shipping);
        $order = $this->order->update(['id' => $order->id, 'admin' => 1]);  // via admin

        session()->forget('reference_no');
        session()->forget('transaction_no');

        flash('La commande a été crée')->success();

        return redirect('admin/orders');
    }

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $name  = $request->input('name');
        $order = $this->order->updateDate(['id' => $request->input('pk'), 'name' => $name, 'value' => $request->input('value')]);

        if($order)
        {
            if($name == 'payed_at') {
                $etat   = ($order->status == 'pending' ? 'En attente' : 'Payé');
                $status = ($order->status == 'pending' ? 'warning' : 'success');
            }

            if($name == 'send_at'){
                $etat   = (!$order->send_at ? 'En attente' : 'Envoyé');
                $status = (!$order->send_at ? 'warning' : 'info');
            }

            return response()->json([
                'OK'    => 200,
                'etat'  => $etat,
                'color' => $status
            ]);
        }

        return response()->json(['status' => 'error','msg' => 'problème']);
    }

    public function update($id, Request $request)
    {
        $order = $this->order->find($id);

        $updater = new \App\Droit\Shop\Order\Worker\OrderUpdate(array_filter($request->all()),$order);
        $order = $updater->updateOrder();

        flash('La commande a été mise à jour')->success();

        return redirect('admin/order/'.$order->id);
    }

    public function recalculate(Request $request)
    {
        $order    = $this->order->update(['id' => $request->input('id'), 'shipping_id' => null, 'paquet' => null]);
        $orderbox = new \App\Droit\Shop\Order\Entities\OrderBox($order);
        $paquets  = $orderbox->calculate($order->weight)->getShippingList();

        $this->order->setPaquets($order,$paquets);

        $messages = isset($order->comment) ? unserialize($order->comment) : null;

        if($messages)
            $this->pdfgenerator->setMsg($messages);

        $this->pdfgenerator->factureOrder($order);

        flash('La commande a été mise à jour')->success();

        return redirect('admin/order/'.$order->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = $this->order->find($id);

        $this->ordermaker->resetQty($order,'+');
        $this->order->delete($id);

        flash('La commande a été annulé')->success();

        return redirect('admin/orders');
    }

    /**
     * Restore the inscription
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $this->order->restore($id);

        flash('La commande a été restauré')->success();

        return redirect('admin/orders');
    }

    public function resume(Request $request)
    {
        $data   = ['period' => ['start' => \Carbon\Carbon::now()->startOfMonth()->toDateString(), 'end' => \Carbon\Carbon::now()->endOfMonth()->toDateString() ]];

        $data   = array_merge($data,$request->except('_token'));
        $orders = $this->order->getPeriod($data);

        $request->flash();

        return view('backend.orders.resume')->with(['orders' => $orders] + $data);
    }

}
