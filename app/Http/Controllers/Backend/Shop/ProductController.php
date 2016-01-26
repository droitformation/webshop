<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Service\UploadInterface;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Abo\Repo\AboInterface;

class ProductController extends Controller {

    protected $upload;
	protected $product;
    protected $categorie;
    protected $order;
    protected $attribute;
    protected $author;
    protected $domain;
    protected $abo;
    protected $helper;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
        ProductInterface $product,
        CategorieInterface $categorie,
        OrderInterface $order,
        AttributeInterface $attribute,
        AuthorInterface $author,
        DomainInterface $domain,
        UploadInterface $upload,
        AboInterface $abo
    )
	{
        $this->product   = $product;
        $this->categorie = $categorie;
        $this->order     = $order;
        $this->attribute = $attribute;
        $this->author    = $author;
        $this->domain    = $domain;
        $this->upload    = $upload;
        $this->abo       = $abo;

        $this->helper    = new \App\Droit\Helper\Helper();
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

        $search = $request->input('search',null);

        if($search)
        {
            $search = array_filter($search);
        }

        $products    = $this->product->getAll($search);

        $attributes  = $this->attribute->getAll();
        $categories  = $this->categorie->getAll();
        $authors     = $this->author->getAll();
        $domains     = $this->domain->getAll();

		return view('backend.products.index')->with(['products' => $products,'attributes' => $attributes, 'categories' => $categories, 'authors' => $authors, 'domains' => $domains]);
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
        $abos        = $this->abo->getAll();

        return view('backend.products.show')->with(['product' => $product,'attributes' => $attributes, 'categories' => $categories, 'authors' => $authors, 'domains' => $domains, 'abos' => $abos]);
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

        return redirect('admin/product')->with(array('status' => 'success' , 'message' => 'Le produit a été supprimé' ));
    }

    public function addAttribut($id, Request $request)
    {
        $product = $this->product->find($id);

        $product->attributs()->attach($request->input('attribute_id'), ['value' => $request->input('value')]);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'attribut a été ajouté' ));
    }

    public function removeAttribut($id, Request $request)
    {

        $product = $this->product->find($id);

        $product->attributs()->detach($request->input('attribute_id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'attribut a été supprimé' ));
    }

    public function addType($id, Request $request)
    {
        $product = $this->product->find($id);
        $types   = $request->input('type');

        $product->$types()->attach($request->input('type_id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Le type a été ajouté' ));
    }

    public function removeType($id, Request $request)
    {
        $product = $this->product->find($id);
        $types   = $request->input('type');

        $product->$types()->detach($request->input('type_id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'attribut a été supprimé' ));
    }
}
