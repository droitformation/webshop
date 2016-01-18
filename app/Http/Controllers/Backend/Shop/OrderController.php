<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Order\Worker\OrderAdminWorkerInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

class OrderController extends Controller {

	protected $product;
    protected $categorie;
    protected $order;
    protected $generator;
    protected $worker;
    protected $adresse;
    protected $shipping;
    protected $helper;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductInterface $product, CategorieInterface $categorie, OrderInterface $order, OrderAdminWorkerInterface $worker, AdresseInterface $adresse)
	{
        $this->product   = $product;
        $this->categorie = $categorie;
        $this->order     = $order;
        $this->worker    = $worker;
        $this->adresse   = $adresse;

        $this->generator = new \App\Droit\Generate\Excel\ExcelGenerator();
        $this->helper    = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

        $names   = config('columns.names');

        $period   = $request->all();
        $status   = $request->input('status',null);
        $onlyfree = $request->input('onlyfree',null);
        $details  = $request->input('details',null);
        $columns  = $request->input('columns',$this->generator->columns);
        $export   = $request->input('export',null);

        $period['start'] = (!isset($period['start']) ? \Carbon\Carbon::now()->startOfMonth() : \Carbon\Carbon::parse($period['start']) );
        $period['end']   = (!isset($period['end'])   ? \Carbon\Carbon::now()->endOfMonth()   : \Carbon\Carbon::parse($period['end']) );

        $orders = $this->order->getPeriod($period['start'],$period['end'], $status, $onlyfree);

        if($export)
        {
            $this->generator->setColumns($columns);
            $this->export($orders,$details);
        }

   /*     echo '<pre>';
        print_r($orders);
        echo '</pre>';exit;*/

		return view('backend.orders.index')->with(['orders' => $orders, 'start' => $period['start'], 'end' => $period['end'], 'columns' => $columns, 'names' => $names, 'onlyfree' => $onlyfree, 'details' => $details]);
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function export($orders, $details = null)
    {
        \Excel::create('Export Commandes', function($excel) use ($orders,$details)
        {
            $excel->sheet('Export_Commandes', function($sheet) use ($orders,$details)
            {
                $names  = config('columns.names');
                $view   = (isset($details) ? 'details' : 'orders');

                $sheet->setOrientation('landscape');
                $sheet->loadView('backend.export.'.$view , ['orders' => $orders , 'generator' => $this->generator, 'names' => $names]);
            });
        })->export('xls');
    }

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        return view('backend.orders.show')->with(['product' => $product]);
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
        $order    = $request->input('order');
        $products = $this->helper->convertProducts($order);

        $validator = \Validator::make($request->all(), [
            'adresse.first_name'  => 'required_without:user_id',
            'adresse.last_name'   => 'required_without:user_id',
            'adresse.adresse'     => 'required_without:user_id',
            'adresse.npa'         => 'required_without:user_id',
            'adresse.ville'       => 'required_without:user_id',
        ], [
            'adresse.first_name.required_without'  => 'Une adresse (prénom) est requise sans utilisateur',
            'adresse.last_name.required_without'   => 'Une adresse (nom) est requise sans utilisateur',
            'adresse.adresse.required_without'     => 'Une adresse (adresse) est requise sans utilisateur',
            'adresse.npa.required_without'         => 'Une adresse (npa) est requise sans utilisateur',
            'adresse.ville.required_without'       => 'Une adresse (ville) est requise sans utilisateur',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->with('old_products', $products)->withInput();
        }

  /*      echo '<pre>';
        print_r($request->all());
        echo '</pre>';exit;*/

        $order = $this->worker->make($request->all());

        return redirect('admin/orders')->with(array('status' => 'success', 'message' => 'La commande a été crée' ));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->product->delete($id);

        return redirect('admin/orders')->with(array('status' => 'success' , 'message' => 'Le produit a été supprimé' ));
    }

}
