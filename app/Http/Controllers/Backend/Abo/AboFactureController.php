<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AboFactureRequest;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Worker\AboFactureWorkerInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboFactureController extends Controller {

    protected $abo;
    protected $abonnement;
    protected $facture;
    protected $rappel;
    protected $product;
    protected $worker;

    public function __construct(AboUserInterface $abonnement, AboInterface $abo, AboFactureInterface $facture, ProductInterface $product, AboRappelInterface $rappel, AboFactureWorkerInterface $worker)
    {
        $this->abo        = $abo;
        $this->abonnement = $abonnement;
        $this->facture    = $facture;
        $this->rappel     = $rappel;
        $this->product    = $product;
        $this->worker     = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id)
    {
        $factures = $this->facture->getAll($id);
        $abo      = $this->abo->findAboByProduct($id);
        $product  = $this->product->find($id);

        $files    = \File::glob('files/abos/bound/'.$abo->id.'/*'.$product->edition_clean.'.pdf');

        return view('backend.abonnements.factures.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id, 'files' => $files, 'product' => $product ]);
    }

    public function show($id)
    {
        $facture = $this->facture->find($id);
        
        return view('backend.abonnements.factures.show')->with([ 'facture' => $facture, 'abo' => $facture->abonnement->abo, 'abonnement' => $facture->abonnement, 'product' => $facture->product ]);
    }

	public function store(AboFactureRequest $request)
	{
        $type = $request->input('type');
        $item = $this->$type->create($request->except('type'));

        $this->worker->make($item);
        
        alert()->success($type.' a été crée');

        return redirect()->back();
	}

    public function update(Request $request, $id)
    {
        $facture = $this->facture->update($request->except('price'));

        // update the price of the abo and remake invoice
        $price = $request->input('price',null);

        if($price)
        {
            $this->abonnement->update(['id' => $facture->abo_user_id, 'price' => $request->input('price') * 100]);
        }

        $this->worker->make($facture);

        alert()->success('La facture a été mis à jour');

        return redirect()->back();
    }
		
	public function destroy($id)
	{
        $this->facture->delete($id);

        alert()->success('La facture a été supprimé');

        return redirect()->back();
	}
    
    /*
     * Ajax Call
     * */
    public function edit(Request $request)
    {
        $facture = $this->facture->update(['id' => $request->input('pk'), $request->input('name') => $request->input('value')]);

        return response()->json(['OK' => 200, 'etat' => (!$facture->payed_at ? 'En attente' : 'Payé'),'color' => (!$facture->payed_at ? 'default' : 'success')]);
    }

    /**
     * Generate the facture
     *
     * @return Response
     */
    public function generate(Request $request)
    {
        $facture = $this->facture->find($request->input('id'));

        $this->worker->make($facture);

        return response()->json(['link' => $facture->doc_facture]);
    }

    /**
     * Generate the facture
     *
     * @return Response
     */
    public function make(Request $request)
    {
        $facture = $this->facture->find($request->input('id'));

        $this->worker->make($facture);

        alert()->success('La facture a été généré');

        return redirect()->back();
    }
    
}