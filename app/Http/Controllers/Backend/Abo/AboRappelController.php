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

        $dir      = 'files/abos/bound/'.$abo->id.'/rappels_'.$product->reference.'_'.$product->edition_clean.'.pdf';
        $files    = \File::glob($dir);

        return view('backend.abonnements.rappels.index')->with(['factures' => $factures, 'abo' => $abo, 'id' => $id, 'files' => $files, 'product' => $product ]);
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

    public function send(Request $request)
    {
        // Make sur we have created all the rappels in pdf
        $job = (new MakeRappelAbo($request->input('rappels')));
        $this->dispatch($job);
        
        //Send the rappels via email
        $job = (new SendRappelAboEmail($request->input('rappels')))->delay(\Carbon\Carbon::now()->addMinutes(1));
        $this->dispatch($job);

        alert()->success('Rappels envoyés');

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