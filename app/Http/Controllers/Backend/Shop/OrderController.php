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

        $orders = $this->order->getPeriod($period['start'],$period['end'], $request->input('status',null), $request->input('onlyfree',null), $request->input('order_no',null));

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

        $request->flash();

		return view('backend.orders.index')
            ->with(['orders' => $orders, 'start' => $period['start'], 'end' => $period['end'],'columns' => config('columns.names'), 'cancelled' => $cancelled] + $data);
	}

    /**
     *
     * @return Response
     */
    public function show($id)
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

        $this->pdfgenerator->factureOrder($order->id);

        alert()->success('La facture a été regénéré');

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->product->getAll();

        return view('backend.orders.create')->with(['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = $this->ordermaker->make($request->all());

        // via admin
        $order->admin = 1;
        $order->save();

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
        $order = $this->order->update([ 'id' => $request->input('pk'), $name =>  $request->input('value')]);

        if($order)
        {
            return response()->json(['OK' => 200, 'etat' => ($order->status == 'pending' ? 'En attente' : 'Payé'),'color' => ($order->status == 'pending' ? 'warning' : 'success')]);
        }

        return response()->json(['status' => 'error','msg' => 'problème']);
    }

    public function update($id, Request $request)
    {
        $coupon = $request->input('coupon_id',null);
        $order  = $this->order->find($request->input('id'));
        
        $coupon = isset($coupon) ? $this->coupon->find($request->input('coupon_id')) : null;

        $data   = $this->ordermaker->updateOrder($order, $request->input('shipping_id'), $coupon);
        $order  = $this->order->update($data);

        if(!empty(array_filter($request->input('tva',[]))))
            $this->pdfgenerator->setTva(array_filter($request->input('tva')));

        if(!empty(array_filter($request->input('message',[]))))
            $this->pdfgenerator->setMsg(array_filter($request->input('message')));

        $this->pdfgenerator->factureOrder($order->id);

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

}
