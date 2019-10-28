<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\ProductAttributRequest;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Reminder\Worker\ReminderWorker;

class ProductAttributeController extends Controller {

	protected $product;
    protected $attribute;
    protected $reminder;

	public function __construct(ProductInterface $product, AttributeInterface $attribute, ReminderWorker $reminder)
	{
        $this->product   = $product;
        $this->attribute = $attribute;
        $this->reminder  = $reminder;
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
            $this->reminder->add($attribute, $product, $request->input('value'), $attribute->duration);
        }

        $product->attributs()->attach($request->input('attribute_id'), ['value' => $request->input('value')]);

        flash('L\'attribut a été ajouté')->success();

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

        flash('L\'attribut a été supprimé')->success();

        return redirect()->back();
    }

}
