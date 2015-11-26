<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboFactureController extends Controller {

    protected $facture;
    protected $product;

    public function __construct(AboFactureInterface $facture, ProductInterface $product)
    {
        $this->facture = $facture;
        $this->product = $product;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	public function store(Request $request)
	{
        $facture = $this->facture->create($request->all());

        return redirect('admin/abo')->with(array('status' => 'success', 'message' => 'La facture a été crée' ));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $facture = $this->facture->update($request->all());

        return redirect('admin/abo/'.$facture->id)->with(array('status' => 'success', 'message' => 'La facture a été mis à jour' ));
    }

    public function make(Request $request)
    {
        $type = $request->input('type');
        $data = $request->except('type');
        $make = 'make'.$type;
        $new  = $this->abonnement->$make($data);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'La '.$type.' a été crée' ));
    }
		
	public function destroy(Request $request)
	{

	}

}