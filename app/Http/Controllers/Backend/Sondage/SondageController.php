<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\SondageInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class SondageController extends Controller
{
    protected $sondage;
    protected $colloque;

    public function __construct(SondageInterface $sondage, ColloqueInterface $colloque)
    {
        $this->sondage  = $sondage;
        $this->colloque = $colloque;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sondages  = $this->sondage->getAll();

        return view('backend.sondages.index')->with(['sondages' => $sondages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $colloques = $this->colloque->getAll(false,false);
        
        return view('backend.sondages.create')->with(['colloque' => $colloques]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $sondage = $this->sondage->create($request->all());

        alert()->success('Le sondage a été crée');

        return redirect('admin/sondage/'.$sondage->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $sondage   = $this->sondage->find($id);
        $colloques = $this->colloque->getAll(false,false);
        
        return view('backend.sondages.show')->with(['sondage' => $sondage, 'colloques' => $colloques]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $sondage = $this->sondage->update($request->all());

        alert()->success('Le sondage a été mis à jour');

        return redirect('admin/sondage/'.$sondage->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->sondage->delete($id);

        alert()->success('Le sondage a été supprimé');

        return redirect('admin/sondage');
    }

    public function remove(Request $request)
    {
        $sondage = $this->sondage->find($request->input('sondage_id'));

        $sondage->questions()->detach($request->input('question_id'));

        return redirect()->back();
    }

    public function sorting(Request $request)
    {
        $data = $request->all();

        $sondage = $this->sondage->updateSorting($request->input('id'), $data['question_rang']);

        echo 'ok';die();
    }
}
