<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class AboController extends Controller {

    protected $abo;
    protected $adresse;
    protected $product;

    public function __construct(AboInterface $abo, AdresseInterface $adresse, ProductInterface $product)
    {
        $this->abo     = $abo;
        $this->adresse = $adresse;
        $this->product = $product;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
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

    public function create()
    {
        $plans    = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];
        $products = $this->product->getAll();

        return view('backend.abos.create')->with(['plans' => $plans, 'products' => $products]);
    }

    public function show($id)
    {
        $abo      = $this->abo->find($id);
        $plans    = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];
        $products = $this->product->getAll();

        return view('backend.abos.edit')->with(['abo' => $abo, 'plans' => $plans, 'products' => $products]);
    }

	public function store(Request $request)
	{
        $abo = $this->abo->create($request->all());

        return redirect('admin/abo')->with(array('status' => 'success', 'message' => 'L\'abo a été crée' ));
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
        $abo  = $this->abo->update($request->all());

        return redirect('admin/abo/'.$abo->id)->with(array('status' => 'success', 'message' => 'L\'abo  a été mis à jour' ));
    }
		
	public function destroy(Request $request)
	{

	}

}