<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\AvisInterface;

class AvisController extends Controller
{
    protected $avis;

    public function __construct(AvisInterface $avis)
    {
        $this->avis = $avis;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $avis = $this->avis->getAll();

        return view('backend.avis.index')->with(['avis' => $avis]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.avis.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $avis = $this->avis->create($request->all());

        alert()->success('La question a été crée');

        return redirect('admin/avis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $avis = $this->avis->find($id);

        return view('backend.avis.show')->with(['avis' => $avis]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $avis = $this->avis->update($request->all());

        alert()->success('La question a été mis à jour');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->avis->delete($id);

        alert()->success('La question a été supprimé');

        return redirect()->back();
    }
}
