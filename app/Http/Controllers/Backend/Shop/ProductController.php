<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class ProductController extends Controller {

	protected $product;
    protected $categorie;
    protected $order;
    protected $attribute;
    protected $author;
    protected $domain;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductInterface $product, CategorieInterface $categorie, OrderInterface $order, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain)
	{
        $this->product   = $product;
        $this->categorie = $categorie;
        $this->order     = $order;
        $this->attribute = $attribute;
        $this->author    = $author;
        $this->domain    = $domain;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $products = $this->product->getAll();

		return view('backend.products.index')->with(['products' => $products]);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $attributes  = $this->attribute->getAll();
        $categories  = $this->categorie->getAll();
        $authors     = $this->author->getAll();
        $domains     = $this->domain->getAll();

        return view('backend.products.create')->with(['attributes' => $attributes, 'categories' => $categories, 'authors' => $authors, 'domains' => $domains]);
    }

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product     = $this->product->find($id);
        $attributes  = $this->attribute->getAll();
        $categories  = $this->categorie->getAll();
        $authors     = $this->author->getAll();
        $domains     = $this->domain->getAll();

        return view('backend.products.show')->with(['product' => $product,'attributes' => $attributes, 'categories' => $categories, 'authors' => $authors, 'domains' => $domains]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product  = $this->product->update($request->all());

        return redirect('admin/product')->with(array('status' => 'success', 'message' => 'Le produit a été mis à jour' ));
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

    public function addAttribut($id, Request $request)
    {
        $product = $this->product->find($id);

        $product->attributes()->attach($request->input('attribute_id'), ['value' => $request->input('value')]);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'attribut a été ajouté' ));
    }

    public function removeAttribut($id, Request $request)
    {
        $product = $this->product->find($id);

        $product->attributes()->detach($request->input('attribute_id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'attribut a été supprimé' ));
    }

}
