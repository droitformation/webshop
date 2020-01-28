<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\AboRequest;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Service\UploadInterface;

class AboController extends Controller {

    protected $abo;
    protected $adresse;
    protected $product;
    protected $upload;

    public function __construct(AboInterface $abo, AdresseInterface $adresse, ProductInterface $product, UploadInterface $upload)
    {
        $this->abo     = $abo;
        $this->adresse = $adresse;
        $this->product = $product;
        $this->upload  = $upload;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

	/**
	 *
	 * @return Response
	 */
	public function index()
	{
        $abos = $this->abo->getAll();

        return view('backend.abos.index')->with(['abos' => $abos]);
	}

    public function create()
    {
        $plans    = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];
        $products = $this->product->forAbos(true); // all with hidden ones

        return view('backend.abos.create')->with(['plans' => $plans, 'products' => $products]);
    }

    public function show($id)
    {
        $abo      = $this->abo->find($id);
        $plans    = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];
        $products = $this->product->forAbos(true);

        return view('backend.abos.edit')->with(['abo' => $abo, 'plans' => $plans, 'products' => $products]);
    }

	public function store(AboRequest $request)
	{
        $data = $request->except('file');
        $file = $request->file('file',null);

        if($file) {
            $file = $this->upload->upload( $file , 'files/main');
            $data['logo'] = $file['name'];
        }

        $abo = $this->abo->create($data);

        flash('L\'abo a été crée')->success();
        
        return redirect('admin/abo');
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
        $data = $request->except('file');
        $file = $request->file('file',null);

        if($file)
        {
            $file = $this->upload->upload( $file , 'files/main');
            $data['logo'] = $file['name'];
        }

        $abo = $this->abo->update($data);

        flash('L\'abo a été mis à jour')->success();

        return redirect('admin/abo/'.$abo->id);
    }
		
	public function destroy($id)
	{
        $abo = $this->abo->find($id);

        if(!$abo->abonnements->isEmpty())
        {
            flash('Il existe des utilisateurs pour cet abo')->warning();

            return redirect()->back();
        }

        $this->abo->delete($id);

        flash('L\'abo a été supprimé')->success();

        return redirect()->back();
	}

    public function desinscription($id)
    {
        $abo = $this->abo->find($id);

        return view('backend.abos.desinscription')->with(['abo' => $abo]);
    }
}