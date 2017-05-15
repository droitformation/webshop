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
	public function index(Request $request, $back = null)
	{
        $data = ['period' => ['start' => \Carbon\Carbon::now()->startOfMonth()->toDateString(), 'end' => \Carbon\Carbon::now()->endOfMonth()->toDateString() ]];

        if($back){
            $session = session()->has('order_search') ? session()->get('order_search') : [];
            $data = array_merge($data,$session);
        }
        else{
            $data = array_filter(array_merge($data,$request->except('_token')));
            session(['order_search' => $data]);
        }

        $orders    = isset($data['order_no']) ? $this->order->search($data['order_no']) : $this->order->getPeriod($data);
        $cancelled = $this->order->getTrashed($data['period']);

        list($orders, $invalid) = $orders->partition(function ($order) {
            return $order->order_adresse;
        });

        if(isset($data['export'])) {
            if($orders->isEmpty()){ alert()->success('Aucune commande à exporter'); }
            $exporter = new \App\Droit\Generate\Export\ExportOrder();
            $details  = isset($data['details']) ? true : null;
            $onlyfree = isset($data['onlyfree']) ? true : null;
            $exporter->setColumns($request->input('columns',config('columns.names')))->setPeriod($data['period'])->setDetail($details)->setFree($onlyfree);
            $exporter->export($orders);
        }

        $request->flash();
        return view('team.orders.index')
            ->with(['orders' => $orders, 'invalid' => $invalid, 'period' => $data['period'], 'columns' => config('columns.names'), 'cancelled' => $cancelled] + $data);
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
