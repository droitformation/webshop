<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class ShopController extends Controller {

	protected $colloque;
	protected $product;
	protected $categorie;
	protected $author;
	protected $domain;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ColloqueInterface $colloque,ProductInterface $product,CategorieInterface $categorie, AuthorInterface $author, DomainInterface $domain)
	{
		$this->colloque  = $colloque;
        $this->product   = $product;
		$this->categorie = $categorie;
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
        $nouveautes = $this->product->getByCategorie(5)->take(6);
		$products   = $this->product->getNbr(10,[5]);
        $colloques  = $this->colloque->getAll(true);

		return view('frontend.pubdroit.index')->with(['products' => $products, 'nouveautes' => $nouveautes, 'colloques' => $colloques]);
	}

	public function products(Request $request)
	{
		$search = $request->input('search',null);

		if($search)
		{
			$search = array_filter($search);
		}

		$products    = $this->product->getAll($search);
		return view('frontend.pubdroit.index')->with(['products' => $products]);
	}

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        return view('shop.show')->with(['product' => $product]);
    }

    public function sort(Request $request)
    {
        $search = $request->input('search',null);

        if($search)
        {
            $title  = $request->input('title','');
            $label  = $request->input('label',null);
            $label  = $label ? $this->$label->find($search[$label.'_id'])->title : '';

            $products = $this->product->getAll($search);
        }
        else
        {
            return redirect('/');
        }

        return view('frontend.pubdroit.products')->with(['products' => $products, 'title' => $title, 'label' => $label]);
    }

}
