<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboRappelController extends Controller {

    protected $rappel;
    protected $facture;
    protected $product;
    protected $generator;

    public function __construct(AboRappelInterface $rappel, AboFactureInterface $facture, ProductInterface $product, PdfGeneratorInterface $generator)
    {
        $this->rappel    = $rappel;
        $this->facture   = $facture;
        $this->product   = $product;
        $this->generator = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	public function store(Request $request)
	{
        $rappel  = $this->rappel->create($request->all());
        $rappel->load('facture');

        $rappels = $this->rappel->findByFacture($request->abo_facture_id);
        $rappels = $rappels->count();

        $this->generator->makeAbo('rappel', $rappel->facture, $rappels, $rappel);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'La rappel a été crée' ));
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
        $rappel = $this->rappel->update($request->all());

        return redirect('admin/abo/'.$rappel->id)->with(array('status' => 'success', 'message' => 'La rappel a été mis à jour' ));
    }
		
	public function destroy($id, Request $request)
	{
        $this->rappel->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Le rappel a été supprimé' ));
	}

}