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

        $this->middleware('abo', ['only' => ['addAbo']]);
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
        $id   = $request->input('abo_id');

        $exist = \Cart::instance('abonnement')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });
        
        if(!$exist->isEmpty()){
            $request->session()->flash('aboAlreadyInCart', 'Abonnement déjà dans les panier');
            return redirect()->back();
        }
        
        \Cart::instance('abonnement')
            ->add($item->id, $item->title, 1, $item->price_cents , [
                'image' => $item->logo,
                'plan' => $item->plan_fr,
                'product_id' => $item->current_product->id,
                'product' => $item->current_product->title,
                'shipping_cents' => $item->shipping_cents
            ])->associate('App\Droit\Abo\Entities\Abo');

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
	}

    public function removeAbo(Request $request){

        \Cart::instance('abonnement')->remove($request->input('rowId'));

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
    }

    public function quantityAbo(Request $request){
        
        \Cart::instance('abonnement')->update($request->input('rowId'), $request->input('qty'));

        $request->session()->flash('cartUpdated', 'Panier mis à jour');

        return redirect()->back();
    }
}
