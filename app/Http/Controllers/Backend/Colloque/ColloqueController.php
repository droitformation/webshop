<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ColloqueRequest;

use App\Droit\Service\UploadInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Document\Worker\DocumentWorker;
use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Droit\Compte\Repo\CompteInterface;

class ColloqueController extends Controller
{
    protected $colloque;
    protected $upload;
    protected $compte;
    protected $location;
    protected $organisateur;
    protected $document;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ColloqueInterface $colloque,
        UploadInterface $upload,
        CompteInterface $compte,
        LocationInterface $location,
        OrganisateurInterface $organisateur,
        DocumentWorker $document
    )
    {
        $this->colloque     = $colloque;
        $this->upload       = $upload;
        $this->compte       = $compte;
        $this->location     = $location;
        $this->organisateur = $organisateur;
        $this->document     = $document;
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

        $years = $years->groupBy(function ($archive, $key) {
            return $archive->start_at->year;
        });

        $years = array_keys($years->toArray());

        return view('backend.colloques.index')->with(['colloques' => $colloques, 'years' => $years]);
    }

    public function archive($year)
    {
        $colloques = $this->colloque->getByYear($year);
        $years     = $this->colloque->getYears();

        $years = $years->groupBy(function ($archive, $key) {
            return $archive->start_at->year;
        });

        $years = array_keys($years->toArray());

        return view('backend.colloques.archive')->with(['colloques' => $colloques, 'years' => $years, 'current' => $year]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $locations     = $this->location->getAll();
        $organisateurs = $this->organisateur->centres();

        return view('backend.colloques.create')->with(['locations' => $locations, 'organisateurs' => $organisateurs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data  = $request->except('file');
        $_file = $request->file('file');

        // illustration
        if($_file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/colloques/illustration');
            $data['image'] = $file['name'];
        }

        $colloque = $this->colloque->create($request->all());

        if(isset($data['image']) && !empty($data['image']))
        {
            $this->document->updateColloqueDoc($colloque->id, ['illustration' => $data['image']]);
        }

        alert()->success('Le colloque a été crée');

        return redirect('admin/colloque/'.$colloque->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $colloque      = $this->colloque->find($id);
        $colloque->load('location','adresse','specialisations','centres','compte','prices','documents','options.groupe');

        $locations     = $this->location->getAll();
        $comptes       = $this->compte->getAll();
        $organisateurs = $this->organisateur->centres();

        return view('backend.colloques.show')->with(['colloque' => $colloque, 'comptes' => $comptes, 'locations' => $locations, 'organisateurs' => $organisateurs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ColloqueRequest $request, $id)
    {
        $colloque     = $this->colloque->update($request->all());
        $illustration = $request->input('illustration',null);

        if($illustration && !empty($illustration))
        {
            $this->document->updateColloqueDoc($id, ['illustration' => $illustration]);
        }

        alert()->success('Le colloque a été mis à jour');

        return redirect('admin/colloque/'.$colloque->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->colloque->delete($id);

        alert()->success('Colloque supprimé');

        return redirect('admin/colloque');
    }
}
