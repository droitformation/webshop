<?php namespace App\Http\Controllers\Team\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;

class OrderController extends Controller {
    
    protected $order;
    protected $export;
    protected $shipping;
    protected $helper;
    
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(OrderInterface $order, ShippingInterface $shipping)
	{
        $this->order         = $order;
        $this->shipping      = $shipping;

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

		return view('team.orders.index')
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

        return view('team.orders.show')->with(['order' => $order,'shippings' => $shippings]);
    }
}
