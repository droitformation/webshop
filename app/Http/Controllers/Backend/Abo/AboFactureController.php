<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

class AboFactureController extends Controller {

    protected $facture;
    protected $rappel;
    protected $product;
    protected $generator;
    protected $worker;

    public function __construct(AboFactureInterface $facture, ProductInterface $product, AboRappelInterface $rappel, PdfGeneratorInterface $generator, AboWorkerInterface $worker)
    {
        $this->facture   = $facture;
        $this->rappel    = $rappel;
        $this->product   = $product;
        $this->generator = $generator;
        $this->worker    = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	public function store(Request $request)
	{
        $type = $request->input('type');
        $item = $this->$type->create($request->except('type'));

        if($type == 'rappel')
        {
            $this->worker->make($request->input('abo_facture_id'), true);
        }
        else
        {
            $this->worker->make($item->id);
        }

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