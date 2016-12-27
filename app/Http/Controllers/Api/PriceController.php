<?php
namespace App\Http\Controllers\Api;

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
        $data = $request->input('price');

        $price    = $this->price->create($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return response()->json(['prices' => $colloque->price_display]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->input('price');
        
        $price    = $this->price->update($data);
        $colloque = $this->colloque->find($data['colloque_id']);
        
        return response()->json(['prices' => $colloque->price_display]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $price = $this->price->find($id);
     
        if($price->inscriptions->count() > 0) {
            return response('ERROR', 400);
        }
        
        $this->price->delete($id);
        $colloque = $this->colloque->find($price->colloque_id);

        return response()->json(['prices' => $colloque->price_display]);
    }

}