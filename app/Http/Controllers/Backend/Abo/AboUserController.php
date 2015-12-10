<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AbonnementRequest;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;

class AboUserController extends Controller {

    protected $abonnement;
    protected $adresse;
    protected $abo;

    public function __construct(AboUserInterface $abonnement, AdresseInterface $adresse, AboInterface $abo)
    {
        $this->abonnement = $abonnement;
        $this->adresse    = $adresse;
        $this->abo        = $abo;

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

        return redirect('admin/abonnement/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été crée' ));
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
        $abonnement  = $this->abonnement->update($request->all());

        return redirect('admin/abo/'.$abonnement->id)->with(array('status' => 'success', 'message' => 'L\'abonné a été mis à jour' ));
    }

	public function destroy(Request $request)
	{

	}
}