<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Service\UploadInterface;
use App\Droit\Reminder\Worker\ReminderWorkerInterface;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Stock\Repo\StockInterface;

class ProductController extends Controller {

    protected $upload;
	protected $product;
    protected $attribute;
    protected $abo;
    protected $order;
    protected $reminder;
    protected $stock;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
        ProductInterface $product,
        OrderInterface $order,
        AttributeInterface $attribute,
        UploadInterface $upload,
        AboInterface $abo,
        ReminderWorkerInterface $reminder,
        StockInterface $stock
    )
	{
        $this->product   = $product;
        $this->order     = $order;
        $this->attribute = $attribute;
        $this->upload    = $upload;
        $this->reminder  = $reminder;
        $this->abo       = $abo;
        $this->stock     = $stock;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $search = $request->input('search',null);
        $search = $search ? array_filter($search) : null;
        $term   = $request->input('term',null);

        // results for search
        if($search)
        {
            $paginate = false;
            $products = $this->product->getAll($search, null, true);
        }
        elseif($term)
        {
            $products = $this->product->search(trim($term),true);
            $paginate = false;
        }
        else // pagination
        {
            $products = $this->product->getNbr(20, null, true);
            $paginate = true;
        }

		return view('backend.products.index')->with(['products' => $products, 'paginate' => $paginate, 'search' => $search, 'term' => $term]);
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
     *
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('file');
        $file = $this->upload->upload( $request->file('file') , 'files/products');

        $data['image'] = $file['name'];

        $product = $this->product->create($data);
        
        // Create a entry in stock history
        $this->stock->create(['product_id' => $product->id, 'amount' => $product->sku, 'motif' => 'Stock initial', 'operator' => '+']);

        return redirect('admin/product/'.$product->id)->with(array('status' => 'success', 'message' => 'Le produit a été crée' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('file');
        $file = $request->file('file',null);

        // Make sur that if it is a abo we have attributes (reference and edition)
        $abo_id = $request->input('abo_id',null);

        if(!empty($abo_id))
        {
            $product = $this->product->find($request->input('id'));

            if(empty($product->reference) || empty($product->edition))
            {
                return redirect()->back()->with(['status' => 'warning', 'message' => 'Le livre doit avoir une référence ainsi que l\'édition comme attributs pour devenir un abonnement']);
            }
        }

        if($file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/products');
            $data['image'] = $file['name'];
        }

        $product  = $this->product->update($request->all());

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Le produit a été mis à jour' ));
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

        return redirect('admin/product')->with(['status' => 'success' , 'message' => 'Le produit a été supprimé']);
    }

    public function addAttribut($id, Request $request)
    {
        $product = $this->product->find($id);

        // See if attribute is a rappel
        $attribute = $this->attribute->find($request->input('attribute_id'));

        if($attribute->reminder)
        {
            $this->reminder->add($attribute, $product, $request->input('value'), $attribute->interval);
        }

        $product->attributs()->attach($request->input('attribute_id'), ['value' => $request->input('value')]);

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'attribut a été ajouté']);
    }

    public function removeAttribut($id, Request $request)
    {
        $product = $this->product->find($id);

        $product->attributs()->detach($request->input('attribute_id'));

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'attribut a été supprimé']);
    }

    public function addType($id, Request $request)
    {
        $product = $this->product->find($id);
        $types   = $request->input('type');

        $product->$types()->sync($request->input('type_id'));

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'objet a été ajouté']);
    }

    public function removeType($id, Request $request)
    {
        $product = $this->product->find($id);
        $types   = $request->input('type');

        $product->$types()->detach($request->input('type_id'));

        return redirect()->back()->with(['status' => 'success', 'message' => 'L\'objet été supprimé']);
    }
}
