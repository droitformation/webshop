<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Product\Repo\ProductInterface;
use Illuminate\Http\Request;
use App\Droit\Shop\Cart\Worker\CartWorker;

class CartController extends Controller {

    protected $product;
    protected $worker;
    protected $money;

    public function __construct(ProductInterface $product, CartWorker $worker)
    {
        $this->product = $product;
        $this->worker  = $worker;
        $this->money   = new \App\Droit\Shop\Product\Entities\Money;
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
        $qty  = 1;

        // is there enough stock to add this product?
        // Get qty of product already in cart
        $rowId = \Cart::instance('shop')->search(['id' => (int)$request->input('product_id')]);

        if($rowId)
        {
            $already = \Cart::instance('shop')->get($rowId[0]);
            $qty     = $already ? $already->qty + 1 : $qty;
        }

        // Compare with sku of product
        $sku = $item->sku - $qty;

        // if not enough throw exception
        if($sku < 0){
            throw new \App\Exceptions\StockCartException('Plus assez de stocks pour cet article');
        }

        \Cart::instance('shop')->associate('Product','App\Droit\Shop\Product\Entities')->add($item->id, $item->title, 1, $item->price_cents , array('image' => $item->image,'weight' => $item->weight));

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
	}

    public function removeProduct(Request $request){

        \Cart::instance('shop')->remove($request->input('rowid'));

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
    }

    public function quantityProduct(Request $request){
        
        \Cart::instance('shop')->update($request->input('rowid'), $request->input('qty'));

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
    }

    public function applyCoupon(Request $request){

        $this->worker->reset()->setCoupon($request->input('coupon'))->applyCoupon();
        
        $request->session()->flash('couponApplyed', 'Le coupon a été appliqué');

        return redirect()->back();
    }

}
