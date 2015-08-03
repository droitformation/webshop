<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Http\Controllers\Controller;

class ShopController extends Controller {

	protected $product;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductInterface $product)
	{
        $this->product = $product;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $products = $this->product->getAll();

		return view('shop.index')->with(['products' => $products]);
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
