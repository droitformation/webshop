<?php

namespace App\Http\Controllers\Backend\Bail;

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

        view()->share('calcantons', config('calculette.cantons'));
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

        alert()->success('Taux crée');

        return redirect('admin/calculette/taux');
    }

    public function update($id,Request $request)
    {
        $this->taux->update( $request->all() );

        alert()->success('Taux mis à jour');

        return redirect('admin/calculette/taux/'.$id);
    }

    public function destroy($id)
    {
        $this->taux->delete($id);

        alert()->success('Taux supprimée');

        return redirect()->back();
    }
}
