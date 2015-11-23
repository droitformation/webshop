<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboInterface;

class AboController extends Controller {

    protected $abo;
    protected $adresse;

    public function __construct(AboInterface $abo, AdresseInterface $adresse)
    {
        $this->abo     = $abo;
        $this->adresse = $adresse;
	}

	/**
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $abos = $this->abo->getAll();

        return view('backend.abos.index')->with(['abos' => $abos]);
	}


	public function store(Request $request)
	{

	}
		
	public function destroy(Request $request)
	{

	}

}