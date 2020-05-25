<?php
namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Price\Repo\PriceLinkInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class PriceController extends Controller {

	protected $price;
    protected $colloque;

    public function __construct(PriceLinkInterface $price, ColloqueInterface $colloque)
    {
        $this->price    = $price;
        $this->colloque = $colloque;
    }
    
    public function store(Request $request)
    {
        parse_str($request->input('data'), $data);

        $price    = $this->price->create($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.prices')->with(['type' => $price->type, 'title' => 'Prix '.$price->type, 'colloque' => $colloque]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        // If we edit the price conversion from 10.00 to 1000
        $name  = $request->input('name');
        $value = ($name == 'price' ? $request->input('value') * 100 : $request->input('value'));

        $price = $this->price->update([ 'id' => $request->input('pk'), $name => $value]);

        if($price)
        {
            return response('OK', 200);exit;
        }

        return response('ERROR', 200);
    }

    public function change(Request $request)
    {
        $price = $this->price->update([ 'id' => $request->input('id'), 'type' => $request->input('value')]);
        
        $colloque_id = $price->colloque_id;
        $colloque    = $this->colloque->find($colloque_id);
        
        echo view('backend.colloques.partials.prices')->with(['type' => $price->type, 'title' => 'Prix '.$price->type, 'colloque' => $colloque]);exit;
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $price       = $this->price->find($id);
        $oldprice    = $price;
        $colloque_id = $price->colloque_id;

        $colloque = $this->colloque->find($colloque_id);

        $prices = $colloque->inscriptions->map(function ($item, $key) {
            return $item->price;
        })->pluck('id');

        if($prices->contains($id))
        {
            return response('ERROR', 400);
        }
        
        $this->price->delete($price->id);

        // Has to be called after the delete so we have the updates prices
        $colloque = $this->colloque->find($colloque_id);

        return view('backend.colloques.partials.prices')->with(['type' => $oldprice->type, 'title' => 'Prix '.$oldprice->type, 'colloque' => $colloque]);
    }

}