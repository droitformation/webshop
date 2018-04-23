<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Product\Repo\ProductInterface;

class ProductController extends Controller {
    
	protected $product;
    
	public function __construct(ProductInterface $product)
	{
        $this->product = $product;
	}
    
	public function index()
	{
        $products = $this->product->getAll();

        $products = $products->map(function ($item, $key) {
            $convert = new \App\Droit\Newsletter\Entities\ContentModel();
            return $convert->product($item);
        });

        return response()->json($products , 200 );
	}
    
    public function show($id)
    {
        $product = $this->product->find($id);

		return [
			'id'          => $product->id,
			'title'       => $product->title,
			'teaser'      => strip_tags($product->teaser),
			'image'       => !empty($product->image) ? secure_asset('files/products/'.$product->image) : null,
			'description' => $product->description,
			'price'       => $product->price_cents,
			'link'        => url('pubdroit/product/').$product->id
		];
    }

    public function remove_link(Request $request)
    {
        $product = $this->product->update(['id' => $request->input('id'), 'download_link' => null]);

        return view('backend.products.partials.link')->with(['product' => $product]);
    }
}
