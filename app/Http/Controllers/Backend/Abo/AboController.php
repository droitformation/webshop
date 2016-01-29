<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;

class AboController extends Controller {

    protected $abo;
    protected $adresse;
    protected $product;
    protected $upload;
    protected $worker;

    public function __construct(AboInterface $abo, AdresseInterface $adresse, ProductInterface $product, UploadInterface $upload, AboWorkerInterface $worker)
    {
        $this->abo     = $abo;
        $this->adresse = $adresse;
        $this->product = $product;
        $this->upload  = $upload;
        $this->worker  = $worker;

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
        $data = $request->except('file');
        $file = $request->file('file',null);

        if($file)
        {
            $file = $this->upload->upload( $file , 'files/main');
            $data['logo'] = $file['name'];
        }

        $abo = $this->abo->create($data);

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
        $data = $request->except('file');
        $file = $request->file('file',null);

        if($file)
        {
            $file = $this->upload->upload( $file , 'files/main');
            $data['logo'] = $file['name'];
        }

        $abo = $this->abo->update($data);

        return redirect('admin/abo/'.$abo->id)->with(array('status' => 'success', 'message' => 'L\'abo  a été mis à jour' ));
    }
		
	public function destroy($id)
	{
        $abo = $this->abo->find($id);

        if(!$abo->abonnements->isEmpty())
        {
            return redirect()->back()->with(array('status' => 'warning', 'message' => 'Il existe des utilisateurs pour cet abo' ));
        }

        $this->abo->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'L\'abo  a été supprimé' ));
	}

    public function export(Request $request)
    {
        $abo_id     = $request->input('abo_id');
        $product_id = $request->input('product_id');
        $type       = $request->input('type');
        $edition    = $request->input('edition');
        $reference  = $request->input('reference','na');

        $reference  = str_replace('/','_',$reference);

        // facture_RJN-155_939
        // Name of the pdf file with all the invoices bound together for a particular edition
        $name = $type.'_'.$reference.'_'.$edition;

        // Type : facture or rappel
        // Directory for edition => product_id
        $dir   = 'files/abos/'.$type.'/'.$product_id;

        // Get all files in directory
        $files = \File::files($dir);

        if(!empty($files))
        {
            $this->worker->merge($files, $name, $abo_id);
        }

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Les factures ont été liés' ));

    }

}