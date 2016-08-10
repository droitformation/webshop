<?php namespace App\Http\Controllers\Frontend\Shop;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Abo\Repo\AboInterface;
use Illuminate\Http\Request;

class AboController extends Controller {

    protected $abo;

    public function __construct(AboInterface $abo)
    {
        $this->abo = $abo;
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
    public function addAbo(Request $request)
	{
        $item = $this->abo->find($request->input('abo_id'));

        \Cart::instance('abonnement')->search(['id' => (int)$request->input('abo_id')]);
        \Cart::instance('abonnement')
            ->associate('Abo','App\Droit\Abo\Entities')
            ->add($item->id, $item->title, 1, $item->price_cents , [
                'image' => $item->logo, 'plan' => $item->plan_fr, 'product_id' => $item->current_product->id, 'product' => $item->current_product->title
            ]);

        return redirect()->back();
	}

    public function removeAbo(Request $request){

        \Cart::instance('abonnement')->remove($request->input('rowid'));

        return redirect()->back();
    }

    public function quantityAbo(Request $request){
        
        \Cart::instance('abonnement')->update($request->input('rowid'), $request->input('qty'));

        return redirect()->back();
    }
}
