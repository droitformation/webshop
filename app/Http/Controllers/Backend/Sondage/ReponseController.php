<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\AvisInterface;
use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Sondage\Repo\SondageInterface;

class ReponseController extends Controller
{
    protected $avis;
    protected $reponse;
    protected $sondage;

    public function __construct(AvisInterface $avis, ReponseInterface $reponse, SondageInterface $sondage)
    {
        $this->avis    = $avis;
        $this->reponse = $reponse;
        $this->sondage = $sondage;
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
    public function show($id, Request $request)
    {
        $sondage = $this->sondage->find($id);

        $sort = $request->input('sort',null);
        $sort = $sort ? $sort : 'reponse_id';
        
        $reponses = $sondage->reponses->map(function ($item, $key) {
            return $item->items->load('response');
        })->flatten()->groupBy(function ($item, $key) use($sort) {
            return $item->$sort;
        });
        
        return view('backend.reponses.show')->with(['sondage' => $sondage, 'reponses' => $reponses, 'sort' => $sort]);
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