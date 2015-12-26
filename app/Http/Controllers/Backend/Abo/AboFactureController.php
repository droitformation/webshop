<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

class AboFactureController extends Controller {

    protected $abo;
    protected $facture;
    protected $rappel;
    protected $product;
    protected $generator;
    protected $worker;

    public function __construct(AboInterface $abo, AboFactureInterface $facture, ProductInterface $product, AboRappelInterface $rappel, PdfGeneratorInterface $generator, AboWorkerInterface $worker)
    {
        $this->abo       = $abo;
        $this->facture   = $facture;
        $this->rappel    = $rappel;
        $this->product   = $product;
        $this->generator = $generator;
        $this->worker    = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id, Request $request)
    {
        $abo      = $this->abo->find($id);
        $product  = $request->input('product_id',null);
        $product  = ($product ? $product : $abo->current_product->id);
        $factures = $this->facture->getAll($product);

        return view('backend.abonnements.factures.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id]);
    }

    public function show($id)
    {
        $facture = $this->facture->find($id);
        $abo     = $this->abo->find($facture->abonnement->abo_id);

        return view('backend.abonnements.factures.show')->with([ 'facture' => $facture, 'abo' => $abo ]);
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

        $this->worker->make($facture->id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'La facture a été mis à jour']);
    }
		
	public function destroy(Request $request)
	{
        $type = $request->input('type');
        $this->$type->delete($request->input('id'));

        return redirect()->back()->with(array('status' => 'success', 'message' => $type.' a été supprimé' ));
	}

}