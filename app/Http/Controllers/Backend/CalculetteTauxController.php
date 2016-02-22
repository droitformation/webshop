<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Calculette\Repo\CalculetteTauxInterface;

class CalculetteTauxController extends Controller
{
    protected $taux;

    public function __construct(CalculetteTauxInterface $taux )
    {
        $this->taux = $taux;

        view()->share('calcantons', config('calculette.cantons') );
    }

    public function index()
    {
        $taux = $this->taux->getAll();

        return view('backend.calculette.taux.index')->with(['taux' => $taux]);
    }

    public function create()
    {
        return view('backend.calculette.taux.create');
    }

    public function show($id)
    {
        $taux = $this->taux->find($id);

        return view('backend.calculette.taux.show')->with(['taux' => $taux]);
    }

    public function store(Request $request)
    {
        $taux = $this->taux->create( $request->all() );

        return redirect('admin/calculette/taux')->with(['status' => 'success' , 'message' => 'Taux crée']);
    }

    public function update($id,Request $request)
    {
        $this->taux->update( $request->all() );

        return redirect('admin/calculette/taux/'.$id)->with(['status' => 'success' , 'message' => 'Taux mis à jour']);
    }

    public function destroy($id)
    {
        $this->taux->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Taux supprimée']);
    }
}
