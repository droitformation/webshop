<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\NotifyJobFinished;
use App\Jobs\MergeRappels;
use App\Jobs\MakeRappelAbo;
use App\Jobs\SendRappelAboEmail;

use App\Droit\Generate\Pdf\PdfGeneratorInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboRappelController extends Controller {

    protected $abo;
    protected $rappel;
    protected $facture;
    protected $product;
    protected $generator;
    protected $worker;

    public function __construct(AboInterface $abo, AboRappelInterface $rappel, AboFactureInterface $facture, ProductInterface $product, PdfGeneratorInterface $generator, AboWorkerInterface $worker)
    {
        $this->abo       = $abo;
        $this->rappel    = $rappel;
        $this->facture   = $facture;
        $this->product   = $product;
        $this->generator = $generator;
        $this->worker    = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}
    
    public function index($id)
    {
        $factures = $this->facture->getRappels($id);


        $abo      = $this->abo->findAboByProduct($id);
        $product  = $this->product->find($id);

        $dir      = 'files/abos/bound/'.$abo->id.'/rappels_'.$product->reference.'_'.$product->edition_clean.'.pdf';
        $files    = \File::glob($dir);

        return view('backend.abonnements.rappels.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id, 'files' => $files, 'product' => $product ]);
    }

	public function store(Request $request)
	{
        $rappel  = $this->rappel->create($request->all());
        $rappel->load('facture');

        $rappels = $this->rappel->findByAllFacture($request->abo_facture_id);
        $rappels = $rappels->count();

        $this->generator->makeAbo('rappel', $rappel->facture, $rappels, $rappel);

        alert()->success('Le rappel a été crée');

        return redirect()->back();
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

        alert()->success('Le rappel a été mis à jour');

        return redirect('admin/abo/'.$rappel->id);
    }


	public function destroy($id, Request $request)
	{
        $this->rappel->delete($id);

        alert()->success('Le rappel a été supprimé');

        return redirect()->back();
	}

    /*
    * Generate all invoices and bind the all
    * */
    public function generate($product_id)
    {
        $abo = $this->abo->findAboByProduct($product_id);

        $this->worker->rappels($product_id, $abo->id);

        alert()->success('La création des rappels est en cours.<br/>Un email vous sera envoyé dès que la génération des rappels sera terminée.');

        return redirect()->back();
    }

    /*
    * Generate all invoices
    * */
    public function bind($product_id)
    {
        $abo     = $this->abo->findAboByProduct($product_id);
        $product = $this->product->find($product_id);

        // Name of the pdf file with all the invoices bound together for a particular edition
        $name = 'rappels_'.$product->reference.'_'.$product->edition;

        // Job for merging documents
        $merge = (new MergeRappels($product->id, $name, $abo->id));
        $this->dispatch($merge);

        $job = (new NotifyJobFinished('Les rappels ont été attachés. Nom du fichier: <strong>'.$name.'</strong>'));
        $this->dispatch($job);

        alert()->success('Les rappels sont re-attachés.<br/>Rafraichissez la page pour mettre à jour le document.');

        return redirect()->back();
    }

    public function send(Request $request)
    {
        //Send the rappels via email
        $job = (new SendRappelAboEmail($request->input('rappels')))->delay(\Carbon\Carbon::now()->addMinutes(1));
        $this->dispatch($job);

        alert()->success('Rappels envoyés');

        return redirect()->back();
    }

    public function rappels($product_id)
    {
        $factures = $this->facture->getRappels($product_id);

        $rappels = $factures->map(function ($item, $key) {
            return ['id' => $item->id, 'name' => $item->abonnement->user_facturation->name, 'numero' => $item->abonnement->numero];
        });

        return response()->json($rappels);
    }
}