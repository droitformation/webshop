<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\PriceLink\Repo\PriceLinkInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class PriceLinkController extends Controller {

	protected $price_link;
    protected $colloque;

    public function __construct(PriceLinkInterface $price_link, ColloqueInterface $colloque)
    {
        $this->price_link = $price_link;
        $this->colloque   = $colloque;
    }

    public function store(Request $request)
    {
        $data = $request->input('price');

        $price_link = $this->price_link->create($data);
        $colloque   = $this->colloque->find($request->input('colloque_id'));

        return response()->json(['prices' => $colloque->price_link_display]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->input('price');

        $price_link = $this->price_link->update([
            'id'    => $id,
            'price' => $data['price'],
            'type'  => $data['type'],
            'description' => $data['description'],
            'rang'      => $data['rang'],
            'remarque'  => $data['remarque'],
            'colloques' => $request->input('relations')
        ]);

        $colloque = $this->colloque->find($request->input('colloque_id'));

        return response()->json(['prices' => $colloque->price_link_display]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $price_link = $this->price_link->find($id);

        if($price_link->inscriptions->count() > 0) {
            return response('ERROR', 400);
        }

        $this->price_link->delete($id);
        $colloque = $this->colloque->find($price_link->colloque_id);

        return response()->json(['prices' => $colloque->price_display]);
    }

}