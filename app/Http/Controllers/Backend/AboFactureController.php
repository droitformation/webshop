<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboFactureController extends Controller {

    protected $facture;
    protected $rappel;
    protected $product;

    public function __construct(AboFactureInterface $facture, ProductInterface $product, AboRappelInterface $rappel)
    {
        $this->facture = $facture;
        $this->rappel  = $rappel;
        $this->product = $product;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	public function store(Request $request)
	{
        $type = $request->input('type');
        $item = $this->$type->create($request->except('type'));

        return redirect()->back()->with(['status' => 'success', 'message' => $type.' a été crée']);
	}

    public function update(Request $request, $id)
    {
        $facture = $this->facture->update($request->all());

        return redirect()->back()->with(['status' => 'success', 'message' => 'La facture a été mis à jour']);
    }
		
	public function destroy(Request $request)
	{
        $type = $request->input('type');
        $this->$type->delete($request->input('id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => $type.' a été supprimé' ));
	}

}