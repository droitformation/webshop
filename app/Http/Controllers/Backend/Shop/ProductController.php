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
	 * Show the application welcome screen to the user.
     * @param  \Illuminate\Http\Request $request
	 * @return Response
	 */
	public function index(Request $request,$back = null)
	{
        if($back){
            $search = session()->get('product_search');
            $sort = isset($search['sort']) && !empty($search['sort']) ? $search['sort'] : null;
            $term = isset($search['term']) && !empty($search['term']) ? $search['term'] : null;
        }
        else{
            $sort = $request->input('sort') ? array_filter($request->input('sort')) : null;
            $term = $request->input('term',null);

            session(['product_search' => [
                'term' => $term,
                'sort' => $sort,
            ]]);
        }

        // results for search
        if($sort) {
            $products = $this->product->getAll($sort, null, true);
        }
        elseif($term) {
            $products = $this->product->search(trim($term),true);
        }
        else{
            $products = $this->product->getNbr(20,false);
        }

		return view('backend.products.index')->with(['products' => $products, 'sort' => $sort, 'term' => $request->input('term')]);
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
        
        if($file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/products');
            $data['image'] = $file['name'];
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
