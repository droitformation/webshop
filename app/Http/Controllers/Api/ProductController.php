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
        return $this->product->getAll();
	}
    
    public function show($id)
    {
        $product = $this->product->find($id);

		return [
			'id'          => $product->id,
			'title'       => $product->title,
			'teaser'      => strip_tags($product->teaser),
			'image'       => !empty($product->image) ? asset('files/products/'.$product->image) : null,
			'description' => $product->description,
			'price'       => $product->price_cents,
			'link'        => url('pubdroit/product/').$product->id
		];
    }
}
