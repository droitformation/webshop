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

        view()->share('calcantons', config('calculette.cantons') );
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

        return redirect('admin/calculette/ipc/'.$id)->with(['status' => 'success' , 'message' => 'Ipc mis à jour']);
    }

    public function destroy($id)
    {
        $this->ipc->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Ipc supprimée']);
    }
}
