<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Jobs\MakeRappelAbo;
use App\Jobs\SendRappelAboEmail;

use App\Droit\Abo\Worker\AboRappelWorkerInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboFactureInterface;

use App\Droit\Shop\Product\Repo\ProductInterface;

class AboRappelController extends Controller {

    protected $abo;
    protected $rappel;
    protected $facture;
    protected $product;
    protected $worker;

    public function __construct(AboInterface $abo, AboRappelInterface $rappel, AboFactureInterface $facture, ProductInterface $product, AboRappelWorkerInterface $worker)
    {
        $this->abo       = $abo;
        $this->rappel    = $rappel;
        $this->facture   = $facture;
        $this->product   = $product;
        $this->worker    = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}
    
    public function index($id)
    {
        $factures = $this->facture->getRappels($id);
        $abo      = $this->abo->findAboByProduct($id);
        $product  = $this->product->find($id);

        $dir      = 'files/abos/bound/'.$abo->id;
        $files    = \File::exists($dir) ? \File::glob($dir.'/rappels_*'.$product->edition_clean.'*.pdf') : collect([]);

        return view('backend.abonnements.rappels.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id, 'files' => $files, 'product' => $product ]);
    }

    public function show($id)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $rappel = $this->rappel->find($id);

        $generator->stream = true;
        $generator->setPrint(true);

        $nbr = $rappel->facture->rappels->isEmpty() ? 1 : $rappel->facture->rappels->count();

        return $generator->makeAbo('rappel', $rappel->facture, $nbr, $rappel);
    }

	public function generate(Request $request)
	{
        $rappel = $this->rappel->create(['abo_facture_id' => $request->input('id')]);

        $this->worker->make($rappel, true);

        return ['rappels' => $rappel->facture->rappel_list];
	}

	public function destroy($id, Request $request)
	{
        $this->rappel->delete($id);

        $facture = $this->facture->find($request->input('item'));

        return ['rappels' => $facture->rappel_list];
	}

    public function confirmation($product_id)
    {
        $factures = $this->facture->getRappels($product_id);
        $product  = $this->product->find($product_id);

        return view('backend.abonnements.rappels.confirmation')->with(['factures' => $factures, 'product' => $product ]);
	}

    public function send(Request $request)
    {
        $factures = $this->facture->getMultiple($request->input('factures'));

        if(!$factures->isEmpty()){
            foreach ($factures as $facture){
                // Make the rappels
                $job = (new MakeRappelAbo($facture))->delay(\Carbon\Carbon::now()->addSeconds(10));
                $this->dispatch($job);
            }

            sleep(5);
            foreach ($factures as $facture){
                //Send the rappels via email
                $job = (new SendRappelAboEmail($facture))->delay(\Carbon\Carbon::now()->addMinutes(1));
                $this->dispatch($job);
            }
        }

        flash('Rappels envoyés')->success();

        return redirect()->back();
    }

    /*
     * Ajax Call
     * */
    public function rappels($product_id)
    {
        $factures = $this->facture->getRappels($product_id);

        $rappels = $factures->map(function ($item, $key) {
            return ['id' => $item->id, 'name' => $item->abonnement->user_facturation->name, 'numero' => $item->abonnement->numero];
        });

        return response()->json($rappels);
    }
}