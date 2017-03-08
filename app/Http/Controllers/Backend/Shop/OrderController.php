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

class OrderController extends Controller {

	protected $product;
    protected $coupon;
    protected $categorie;
    protected $order;
    protected $export;
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

        $this->export = new \App\Droit\Generate\Excel\ExcelOrder();
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
	public function index(Request $request)
	{
        $data = $request->all();
      
        $period['start'] = (!isset($data['start']) ? \Carbon\Carbon::now()->startOfMonth() : \Carbon\Carbon::parse($data['start']) );
        $period['end']   = (!isset($data['end'])   ? \Carbon\Carbon::now()->endOfMonth()   : \Carbon\Carbon::parse($data['end']) );

        if($request->input('order_no',null)) {
            $orders = $this->order->search($request->input('order_no',null));
        }
        else {
            $orders = $this->order->getPeriod($period, $request->input('status',null), $request->input('send',null), $request->input('onlyfree',null));
        }
        
        if($request->input('export',null))
        {
            $exporter = new \App\Droit\Generate\Export\ExportOrder();

            $exporter->setColumns($request->input('columns',config('columns.names')))
                     ->setPeriod($period)
                     ->setDetail($request->input('details',null))
                     ->setFree($request->input('onlyfree',null));

            $exporter->export($orders);
        }

        $cancelled = $this->order->getTrashed($period['start'],$period['end']);

        list($orders, $invalid) = $orders->partition(function ($order) {
            return $order->order_adresse;
        });

        $request->flash();

		return view('backend.orders.index')
            ->with([
                'orders'  => $orders,
                'invalid' => $invalid,
                'start'   => $period['start'],
                'end'     => $period['end'],
                'columns' => config('columns.names'),
                'cancelled' => $cancelled] + $data);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shipping = $request->input('shipping_id',null) ? $this->shipping->find($request->input('shipping_id')) : null;

        $order = $this->ordermaker->make($request->all(),$shipping);
        $this->order->update(['id' => $order->id, 'admin' => 1]);  // via admin

        alert()->success('La commande a été crée');

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
        $order  = $this->order->find($request->input('id'));
        
        if($request->input('created_at',null)){
            $order  = $this->order->update([
                'id'         => $request->input('id'),
                'created_at' => $request->input('created_at'),
                'paquet'     => $request->input('paquet',null)
            ]);
        }

        $coupon = $request->input('coupon_id',null) ? $this->coupon->find($request->input('coupon_id')) : null;

        // Prepare data and update
        $data   = $this->ordermaker->updateOrder($order, $request->input('shipping_id'), $coupon);
        $order  = $this->order->update($data);

        if(!empty(array_filter($request->input('tva',[]))))
            $this->pdfgenerator->setTva(array_filter($request->input('tva')));

        if(!empty(array_filter($request->input('message',[]))))
            $this->pdfgenerator->setMsg(array_filter($request->input('message')));

        $this->pdfgenerator->factureOrder($order);

        alert()->success('La commande a été mise à jour');

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

        alert()->success('La commande a été annulé');

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

        alert()->success('La commande a été restauré');

        return redirect('admin/orders');
    }

    public function resume(Request $request)
    {
        $data   = $request->all();
 
        $status = $request->input('status',null) ? $request->input('status') : 'pending';
        $send   = $request->input('send',null)   ? $request->input('send') : 'pending';

        $period['start'] = (!isset($data['start']) ? \Carbon\Carbon::now()->startOfMonth() : \Carbon\Carbon::parse($data['start']) );
        $period['end']   = (!isset($data['end'])   ? \Carbon\Carbon::now()->endOfMonth()   : \Carbon\Carbon::parse($data['end']) );

        $orders = $this->order->getPeriod($period, $request->input('status',null), $request->input('send',null), $request->input('onlyfree',null));

        $request->flash();

        return view('backend.orders.resume')
            ->with(['orders' => $orders, 'status' => $status, 'send' => $send, 'start' => $period['start'], 'end' => $period['end']] + $data);
    }

}
