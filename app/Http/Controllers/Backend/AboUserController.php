<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboUserInterface;

class AboUserController extends Controller {

    protected $abonnement;
    protected $adresse;

    public function __construct(AboUserInterface $abonnement, AdresseInterface $adresse)
    {
        $this->abonnement = $abonnement;
        $this->adresse    = $adresse;
	}

    public function show($id){

        $abonnement = $this->abonnement->find($id);

        return view('backend.abos.abonnement')->with(['abonnement' => $abonnement]);
    }

	public function store(Request $request)
	{

	}
		
	public function destroy(Request $request)
	{

	}
}