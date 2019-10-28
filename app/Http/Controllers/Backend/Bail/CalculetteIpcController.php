<?php

namespace App\Http\Controllers\Backend\Bail;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Calculette\Repo\CalculetteIpcInterface;

class CalculetteIpcController extends Controller
{
    protected $ipc;

    public function __construct(CalculetteIpcInterface $ipc)
    {
        $this->ipc  = $ipc;

        view()->share('calcantons', config('calculette.cantons'));
        view()->share('site_slug', 'bail');
        view()->share('current_site', 2);
    }

    public function index()
    {
        $ipcs = $this->ipc->getAll();

        return view('backend.calculette.ipc.index')->with(['ipcs' => $ipcs]);
    }

    public function create()
    {
        return view('backend.calculette.ipc.create');
    }

    public function show($id)
    {
        $ipc = $this->ipc->find($id);

        return view('backend.calculette.ipc.show')->with(['ipc' => $ipc]);
    }

    public function store(Request $request)
    {
        $ipc = $this->ipc->create( $request->all() );

        return redirect('admin/calculette/ipc')->with(['status' => 'success' , 'message' => 'Ipc crée']);
    }

    public function update($id,Request $request)
    {
        $this->ipc->update( $request->all() );

        flash('Ipc mis à jour')->success();

        return redirect('admin/calculette/ipc/'.$id);
    }

    public function destroy($id)
    {
        $this->ipc->delete($id);

        flash('Ipc supprimée')->success();

        return redirect()->back();
    }
}
