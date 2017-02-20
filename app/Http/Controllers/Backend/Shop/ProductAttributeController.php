<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\ProductAttributRequest;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class ProductAttributeController extends Controller {

	protected $product;
    protected $attribute;

	public function __construct(ProductInterface $product, AttributeInterface $attribute)
	{
        $this->product   = $product;
        $this->attribute = $attribute;
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAttributRequest $request)
    {
        $product = $this->product->find($request->input('product_id'));

        // See if attribute is a rappel
        $attribute = $this->attribute->find($request->input('attribute_id'));

        if($attribute->reminder)
        {
            $this->reminder->add($attribute, $product, $request->input('value'), $attribute->interval);
        }

        $product->attributs()->attach($request->input('attribute_id'), ['value' => $request->input('value')]);

        alert()->success('L\'attribut a été ajouté');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $product = $this->product->find($request->input('product_id'));

        $product->attributs()->detach($id);

        alert()->success('L\'attribut a été supprimé');

        return redirect()->back();
    }

}
