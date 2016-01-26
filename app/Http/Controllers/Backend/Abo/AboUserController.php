<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AbonnementRequest;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;

class AboUserController extends Controller {

    protected $abonnement;
    protected $adresse;
    protected $abo;
    protected $facture;
    protected $worker;

    public function __construct(AboUserInterface $abonnement, AdresseInterface $adresse, AboInterface $abo, AboFactureInterface $facture, AboWorkerInterface $worker)
    {
        $this->abonnement = $abonnement;
        $this->adresse    = $adresse;
        $this->abo        = $abo;
        $this->facture    = $facture;
        $this->worker     = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id, Request $request){

        $product_id  = $request->input('product_id', null);

        $id  = ($product_id ? $product_id : $id);
        $abo = $this->abo->find($id);

        $dir   = './files/abos/bound/'.$id;
        $files = \File::files($dir);

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
        $abonnement = $this->abonnement->create($request->all());

        if($abonnement->status == 'abonne')
        {
            $facture = $this->abonnement->makeFacture(['abo_user_id' => $abonnement->id, 'product_id' => $request->input('product_id')]);

            $this->worker->make($facture->id);
        }

        return redirect('admin/abonnement/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été crée' ));
    }

    public function update(Request $request, $id)
    {
        $abonnement = $this->abonnement->update($request->all());

        $this->worker->update($abonnement);

        return redirect('admin/abonnement/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été mis à jour' ));
    }

	public function destroy($id)
	{
        $this->abonnement->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'abonné a été supprimé' ));
	}


    public function restore($id)
    {
        $this->abonnement->restore($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'abonnemebt a été restauré' ));
    }

}