<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AbonnementRequest;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;

class AboUserController extends Controller {

    protected $abonnement;
    protected $adresse;
    protected $abo;
    protected $worker;

    public function __construct(AboUserInterface $abonnement, AdresseInterface $adresse, AboInterface $abo, AboWorkerInterface $worker)
    {
        $this->abonnement = $abonnement;
        $this->adresse    = $adresse;
        $this->abo        = $abo;
        $this->worker     = $worker;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    public function index($id){

        $abo = $this->abo->find($id);

        return view('backend.abos.show')->with(['abo' => $abo]);
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

        $this->worker->make($abonnement->id);

        return redirect('admin/abonnement/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été crée' ));
    }

    public function update(Request $request, $id)
    {
        $abonnement = $this->abonnement->update($request->all());

        $this->worker->make($abonnement->id);

        return redirect('admin/abo/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été mis à jour' ));
    }

	public function destroy($id)
	{
        $this->abonnement->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'abonné a été supprimé' ));
	}
}