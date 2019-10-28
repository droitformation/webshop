<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Product\Repo\ProductInterface;

class ProductLabelController extends Controller {

	protected $product;

	public function __construct(ProductInterface $product)
	{
        $this->product = $product;
	}

    public function store(Request $request)
    {
        $product = $this->product->find($request->input('product_id'));
        $types   = $request->input('type');

        $product->$types()->sync($request->input('type_id'));

        flash('L\'objet a été ajouté')->success();

        return redirect()->back();
    }

    public function destroy($id,Request $request)
    {
        $product = $this->product->find($request->input('product_id'));
        $types   = $request->input('type');

        $product->$types()->detach($id);

        flash('L\'objet a été supprimé')->success();

        return redirect()->back();
    }
}
