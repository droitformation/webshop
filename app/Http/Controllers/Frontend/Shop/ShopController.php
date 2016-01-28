<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class ShopController extends Controller {

	protected $colloque;
	protected $product;
	protected $categorie;
	protected $attribute;
	protected $author;
	protected $domain;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ColloqueInterface $colloque,ProductInterface $product,  CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain)
	{
		$this->colloque  = $colloque;
        $this->product   = $product;
		$this->categorie = $categorie;
		$this->attribute = $attribute;
		$this->author    = $author;
		$this->domain    = $domain;

		view()->share('categories', $this->categorie->getAll()->pluck('title','id'));
		view()->share('attributes', $this->attribute->getAll()->pluck('title','id'));
		view()->share('authors', $this->author->getAll()->take(6)->pluck('name','id'));
		view()->share('domains', $this->domain->getAll()->take(6)->pluck('title','id'));

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

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        return view('shop.show')->with(['product' => $product]);
    }

}
