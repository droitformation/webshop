<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AbonnementRequest;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Worker\AboFactureWorkerInterface;

class AboUserController extends Controller {

    protected $abonnement;
    protected $adresse;
    protected $abo;
    protected $facture;
    protected $worker;

    public function __construct(AboUserInterface $abonnement, AdresseInterface $adresse, AboInterface $abo, AboFactureInterface $facture, AboFactureWorkerInterface $worker)
    {
        $this->abonnement = $abonnement;
        $this->adresse    = $adresse;
        $this->abo        = $abo;
        $this->facture    = $facture;
        $this->worker     = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id, Request $request){
        
        $id  = ($request->input('product_id', null) ? $request->input('product_id') : $id);
        $abo = $this->abo->find($id);

        // Get files bound froma all factures
        $dir   = './files/abos/bound/'.$id;
        $files = \File::exists($dir) ? \File::files($dir) : [];

        return view('backend.abos.show')->with(['abo' => $abo, 'files' => $files]);
    }

    public function create($id){

        $abo = $this->abo->find($id);

        return view('backend.abonnements.create')->with(['abo' => $abo]);
    }

    public function show($id){

        $abonnement = $this->abonnement->find($id);

        return view('backend.abonnements.show')->with(['abonnement' => $abonnement]);
    }

    public function store(AbonnementRequest $request)
    {
        $abo = $this->abo->find($request->input('abo_id'));

        if($abo->products->isEmpty()){
            flash('Aucun livre attaché à cet abonnement!')->error();
            return redirect()->back()->withInput();
        }

        $abonnement = $this->abonnement->create($request->all());

        // References
        session()->put('reference_no', $request->input('reference_no',null));
        session()->put('transaction_no', $request->input('transaction_no',null));

        $reference = \App\Droit\Transaction\Reference::make($abonnement);

        if($abonnement->status == 'abonne') {
            $facture = $this->abonnement->makeFacture(['abo_user_id' => $abonnement->id, 'product_id' => $request->input('product_id')]);
            $this->worker->make($facture);
        }

        flash('L\'abonné a été crée')->success();

        return redirect('admin/abonnement/'.$abonnement->id);
    }

    public function update(Request $request, $id)
    {
        $abonnement = $this->abonnement->update($request->except(['reference_no','transaction_no']));
        $reference  = \App\Droit\Transaction\Reference::update($abonnement, $request->only(['reference_no','transaction_no']));

        flash('L\'abonné a été mis à jour')->success();

        return redirect('admin/abonnement/'.$abonnement->id);
    }

	public function destroy($id, Request $request)
	{
	    if(!empty($request->input('raison'))){
            $this->abonnement->update(['id' => $id, 'raison' => $request->input('raison'), 'deleted_at' => $request->input('deleted_at')]);
        }
        else{
            $this->abonnement->delete($id);
        }

        flash('L\'abonné a été supprimé')->success();

        return redirect()->back();
	}

    public function restore($id)
    {
        $this->abonnement->restore($id);

        flash('L\'abonné a été restauré')->success();

        return redirect()->back();
    }

}