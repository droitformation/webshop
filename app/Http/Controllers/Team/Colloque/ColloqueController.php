<?php

namespace App\Http\Controllers\Team\Colloque;

use App\Http\Controllers\Controller;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;

use Illuminate\Http\Request;
use App\Http\Requests;

class ColloqueController extends Controller
{
    protected $inscription;
    protected $colloque;
    
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $colloques = $this->colloque->getCurrent(true,false);
        $years     = $this->colloque->getYears();

        $years = $years->map(function ($archive, $key) {
            return $archive->start_at->year;
        })->unique()->values()->toArray();

        return view('team.colloques.index')->with(['colloques' => $colloques, 'years' => $years]);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @param  Request  $request
     * @return Response
     */
    public function show($id)
    {
        $colloque     = $this->colloque->find($id);
        $inscriptions = $this->inscription->getByColloque($id,false,true);
        
        // Filter to remove inscriptions without all infos
        $inscriptions_filter = $inscriptions->filter(function ($inscription, $key) {
            $display = new \App\Droit\Inscription\Entities\Display($inscription);
            return $display->isValid();
        });
  
        return view('team.colloques.show')->with(['inscriptions' => $inscriptions, 'inscriptions_filter' => $inscriptions_filter, 'colloque' => $colloque, 'names' => config('columns.names')]);
    }
    
    public function archive($year)
    {
        $colloques = $this->colloque->getByYear($year);
        $years     = $this->colloque->getYears();

        $years = $years->map(function ($archive, $key) {
            return $archive->start_at->year;
        })->unique()->values()->toArray();

        return view('team.colloques.archive')->with(['colloques' => $colloques, 'years' => $years, 'current' => $year]);
    }
}
