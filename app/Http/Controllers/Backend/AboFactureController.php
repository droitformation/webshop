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
        echo '<pre>';
        print_r($request->all());
        echo '</pre>';exit;

        $type = $request->input('type');
        $item = $this->$type->create($request->except('type'));

        return redirect()->back()->with(['status' => 'success', 'message' => 'La facture a été crée']);
	}

    public function update(Request $request, $id)
    {
        $facture = $this->facture->update($request->all());

        return redirect()->back()->with(['status' => 'success', 'message' => 'La facture a été mis à jour']);
    }
		
	public function destroy(Request $request)
	{

	}

}