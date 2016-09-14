<?php
namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Price\Repo\PriceInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class PriceController extends Controller {

	protected $price;
    protected $colloque;

    public function __construct(PriceInterface $price, ColloqueInterface $colloque)
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

        return response('OK', 200)->with(['status' => 'error','msg' => 'problème']);
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

        $this->price->delete($price->id);

        // Has to be called after the delete so we have the updates prices
        $colloque = $this->colloque->find($colloque_id);

        return view('backend.colloques.partials.prices')->with(['type' => $oldprice->type, 'title' => 'Prix '.$oldprice->type, 'colloque' => $colloque]);
    }

}