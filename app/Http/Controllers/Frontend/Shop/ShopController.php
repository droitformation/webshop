<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class ShopController extends Controller {

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
	public function __construct(ProductInterface $product,  CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain)
	{
        $this->product   = $product;
		$this->categorie = $categorie;
		$this->attribute = $attribute;
		$this->author    = $author;
		$this->domain    = $domain;

		view()->share('categories', $this->categorie->getAll()->pluck('title','id'));
		view()->share('attributes', $this->attribute->getAll()->pluck('title','id'));
		view()->share('authors', $this->author->getAll()->pluck('name','id'));
		view()->share('domains', $this->domain->getAll()->pluck('title','id'));

	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $products = $this->product->getAll()->take(10);

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

}
