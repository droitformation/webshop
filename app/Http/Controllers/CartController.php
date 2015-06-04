<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Product\Repo\ProductInterface;
use Illuminate\Http\Request;

class CartController extends Controller {

    protected $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * Add a row to the cart
     *
     * @param string|Array $id      Unique ID of the item|Item formated as array|Array of items
     * @param string       $name    Name of the item
     * @param int          $qty     Item qty to add to the cart
     * @param float        $price   Price of one item
     * @param Array        $options Array of additional options, such as 'size' or 'color'
     */
    public function addProduct(Request $request)
	{
        $item = $this->product->find($request->input('product_id'));

        \Cart::add($item->id, $item->title, 1, $item->price);

        return redirect()->back();
	}

    public function removeProduct(Request $request){

        \Cart::remove($request->input('rowid'));

        return redirect()->back();
    }

}
