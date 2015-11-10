<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;

class OrderController extends Controller {

	protected $product;
    protected $categorie;
    protected $order;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductInterface $product, CategorieInterface $categorie, OrderInterface $order)
	{
        $this->product   = $product;
        $this->categorie = $categorie;
        $this->order     = $order;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $period = $request->all();

        if(empty($period))
        {
            $period['start'] = \Carbon\Carbon::now()->startOfMonth();
            $period['end']   = \Carbon\Carbon::now()->endOfMonth();
        }

        $orders = $this->order->getPeriod($period['start'],$period['end']);

		return view('backend.orders.index')->with(['orders' => $orders]);
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
        return view('backend.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product  = $this->product->create($request->all());

        return redirect('admin/product')->with(array('status' => 'success', 'message' => 'Le produit a été crée' ));
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

        return redirect('admin/product')->with(array('status' => 'success' , 'message' => 'Le produit a été supprimé' ));
    }

}
