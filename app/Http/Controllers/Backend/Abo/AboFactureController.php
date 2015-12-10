<?php namespace App\Http\Controllers\Backend\Abo;

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

        return redirect()->back()->with(array('status' => 'success', 'message' => 'La facture a été crée' ));
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

        return redirect()->back()->with(array('status' => 'success', 'message' => 'La facture a été mise à jour' ));
    }
		
	public function destroy(Request $request)
	{

	}

}