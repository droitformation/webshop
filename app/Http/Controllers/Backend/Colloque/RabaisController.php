<?php namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\RabaisInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class RabaisController extends Controller
{
    protected $rabais;
    protected $colloque;

    public function __construct( RabaisInterface $rabais, ColloqueInterface $colloque )
    {
        $this->rabais   = $rabais;
        $this->colloque = $colloque;
    }

    public function index()
    {
        $rabais  = $this->rabais->getAll();

        return view('backend.rabais.index')->with(['rabais' => $rabais]);
    }
    
    public function create()
    {
        $colloques = $this->colloque->getCurrent(true,false);

        return view('backend.rabais.create')->with(['colloques' => $colloques]);
    }

    public function store(Request $request)
    {
        $rabai = $this->rabais->create( $request->all() );

        flash('Rabais crée')->success();

        return redirect('admin/rabais/'.$rabai->id);
    }
    
    public function show($id)
    {
        $colloques = $this->colloque->getCurrent(true,false);
        $rabais    = $this->rabais->find($id);

        return view('backend.rabais.show')->with(['rabais' => $rabais, 'colloques' => $colloques]);
    }

    public function update($id,Request $request)
    {
        $this->rabais->update( $request->all() );

        flash('Rabais mise à jour')->success();

        return redirect('admin/rabais/'.$id);
    }


    public function destroy($id)
    {
        $this->rabais->delete($id);

        flash('Rabais supprimé')->success();

        return redirect()->back();
    }

    public function search($term,$colloque_id)
    {
        $result = $this->rabais->search($term);
        $rabais = $result ? $result->id : null;

        $valid   = $result ? true : false;
        $message = $result ? 'Valide' : 'Ce rabais n\'est pas valide';
        $value   = $result->value.' CHF de moins';

        if($result && $result->type == 'colloque' && !$result->colloques->isEmpty()){
            $valid   = $result->colloques->contains('id', $colloque_id) ? true : false;
            $message = !$valid ? 'Ce rabais n\'est pas valide pour ce colloque' : $message;
        }

        return response()->json(['result' => $valid, 'message' => $message, 'value' => $value, 'rabais' => $rabais]);
    }

}
