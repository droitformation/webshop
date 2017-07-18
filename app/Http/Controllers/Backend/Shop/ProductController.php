<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Service\UploadInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Stock\Repo\StockInterface;

class ProductController extends Controller {

    protected $upload;
	protected $product;
    protected $abo;
    protected $order;
    protected $stock;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductInterface $product, OrderInterface $order, UploadInterface $upload, AboInterface $abo, StockInterface $stock)
	{
        $this->product   = $product;
        $this->order     = $order;
        $this->upload    = $upload;
        $this->abo       = $abo;
        $this->stock     = $stock;
	}

	/**
     * @param  \Illuminate\Http\Request $request
     * @param  String $back
	 * @return Response
	 */
	public function index(Request $request,$back = null)
	{
        // Get session search terms or sort if any when we return from a page else get request inputs with defaults
        $data = ['sort' => [], 'term' => null];
        $data = $back ? session()->get('product_search') : array_merge($data,$request->except('_token'));
        array_walk_recursive($data, 'trim');

        // Put search terms and sort in session
        session(['product_search' => $data]);

        // Return products if sorted or search results or else paginate
        $products = $this->product->getList($data);

		return view('backend.products.index')->with(['products' => $products, 'sort' => $data['sort'], 'term' => $data['term']]);
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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        $abos    = $this->abo->getAll();

        return view('backend.products.show')->with(['product' => $product, 'abos' => $abos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('file');

        if($request->file('file',null)) {
            $file = $this->upload->upload( $request->file('file') , public_path('files/products'));
            $data['image'] = $file['name'];
        }

        if($request->file('download_link',null)) {
            $file = $this->upload->upload( $request->file('download_link') , public_path('files/downloads'));
            $data['download_link'] = $file['name'];
        }

        $product = $this->product->create($data);
        
        // Create a entry in stock history
        $this->stock->create(['product_id' => $product->id, 'amount' => $product->sku, 'motif' => 'Stock initial', 'operator' => '+']);

        alert()->success('Le produit a été crée');

        return redirect('admin/product/'.$product->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('file');
        $file = $request->file('file',null);
     
        // Make sur that if it is a abo we have attributes (reference and edition)
        $product = $this->product->find($request->input('id'));
        
        if($request->input('abo_id',null)){
            $validator = new \App\Droit\Shop\Product\Worker\ProductValidation($product);
            $validator->activate();
        }
        
        if($file) {
            $file = $this->upload->upload( $request->file('file') , 'files/products');
            $data['image'] = $file['name'];
        }

        if($request->file('download_link',null)) {
            $file = $this->upload->upload( $request->file('download_link') , public_path('files/downloads'));
            $data['download_link'] = $file['name'];
        }

        $product = $this->product->update($data);

        alert()->success('Le produit a été mis à jour');

        return redirect()->back();
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

        alert()->success('Le produit a été supprimé');

        return redirect('admin/product');
    }
}
