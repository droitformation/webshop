<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\NotifyJobFinished;
use App\Jobs\MergeFactures;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

class AboFactureController extends Controller {

    protected $abo;
    protected $abonnement;
    protected $facture;
    protected $rappel;
    protected $product;
    protected $generator;
    protected $worker;

    public function __construct(AboUserInterface $abonnement, AboInterface $abo, AboFactureInterface $facture, ProductInterface $product, AboRappelInterface $rappel, PdfGeneratorInterface $generator, AboWorkerInterface $worker)
    {
        $this->abo        = $abo;
        $this->abonnement = $abonnement;
        $this->facture    = $facture;
        $this->rappel     = $rappel;
        $this->product    = $product;
        $this->generator  = $generator;
        $this->worker     = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id)
    {
        $factures = $this->facture->getAll($id);
        $abo      = $this->abo->findAboByProduct($id);
        $product  = $this->product->find($id);

        $dir      = 'files/abos/bound/'.$abo->id.'/*_'.$product->reference.'_'.$product->edition.'.pdf';
        $files    = \File::glob($dir);

        return view('backend.abonnements.factures.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id, 'files' => $files, 'product' => $product ]);
    }

    public function show($id)
    {
        $facture    = $this->facture->find($id);
        $abo        = $this->abo->find($facture->abonnement->abo_id);
        $abonnement = $this->abonnement->find($facture->abo_user_id);
        $product    = $this->product->find($facture->product_id);

        return view('backend.abonnements.factures.show')->with([ 'facture' => $facture, 'abo' => $abo, 'abonnement' => $abonnement, 'product' => $product ]);
    }

	public function store(Request $request)
	{
        $type = $request->input('type');
        $item = $this->$type->create($request->except('type'));

        if($type == 'rappel')
        {
            $this->worker->make($request->input('abo_facture_id'), $item);
        }
        else
        {
            $this->worker->make($item->id);
        }

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
            $abonnement = $this->abonnement->find($facture->abo_user_id);
            $abonnement->price = $request->input('price') * 100;
            $abonnement->save();
        }

        $this->worker->make($facture->id);

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
     * Generate all invoices and bind the all
     * */
    public function generate($product_id)
    {
        $abo = $this->abo->findAboByProduct($product_id);

        $this->worker->generate($abo, $product_id);

        alert()->success('La création des factures est en cours.<br/>Un email vous sera envoyé dès que la génération des factures sera terminée.');

        return redirect()->back();
    }

    /*
    * Bind all invoices
    * */
    public function bind($product_id)
    {
        $abo     = $this->abo->findAboByProduct($product_id);
        $product = $this->product->find($product_id);

        // Name of the pdf file with all the invoices bound together for a particular edition
        $name = 'factures_'.$product->reference.'_'.$product->edition;

        // Job for merging documents
        $merge = (new MergeFactures($product->id, $name, $abo->id));
        $this->dispatch($merge);

        $job = (new NotifyJobFinished('Les factures ont été attachés. Nom du fichier: '.$name));
        $this->dispatch($job);

        alert()->success('Les factures sont re-attachés.<br/>Rafraichissez la page pour mettre à jour le document.');

        return redirect()->back();
    }
}