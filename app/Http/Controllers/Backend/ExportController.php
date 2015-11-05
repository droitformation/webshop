<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Worker\InscriptionWorker;

use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Specialisation\Repo\SpecialisationInterface;
use App\Droit\Member\Repo\MemberInterface;

class ExportController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $worker;
    protected $profession;
    protected $canton;
    protected $pays;
    protected $specialisation;
    protected $member;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ColloqueInterface $colloque,
        InscriptionInterface $inscription,
        InscriptionWorker $worker,
        ProfessionInterface $profession,
        PaysInterface $pays,
        CantonInterface $canton,
        MemberInterface $member,
        SpecialisationInterface $specialisation
    )
    {
        $this->inscription    = $inscription;
        $this->colloque       = $colloque;
        $this->worker         = $worker;
        $this->profession     = $profession;
        $this->canton         = $canton;
        $this->pays           = $pays;
        $this->member         = $member;
        $this->specialisation = $specialisation;

        $this->generator   = new \App\Droit\Generate\Excel\ExcelGenerator();
        $this->helper      = new \App\Droit\Helper\Helper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function inscription($id)
    {
        $order    = false;
/*
        $colloque = $this->colloque->find($id);

        $inscriptions = $this->generator->init($colloque, ['order' => $order]);
        $options      = $this->generator->getMainOptions();
        $groupes      = $this->generator->getGroupeOptions();

        return view('export.inscription')->with(['inscriptions' => $inscriptions, 'colloque' => $colloque, 'order' => $order, 'options' => $options, 'groupes' => $groupes]);*/

        ////////////////////////////////////////////////////////////////////////////////////////

        \Excel::create('Export inscriptions', function($excel) use ($id,$order) {

            $excel->sheet('Export', function($sheet) use ($id,$order) {

                $colloque = $this->colloque->find($id);

                $inscriptions = $this->generator->init($colloque, ['order' => $order]);
                $options      = $this->generator->getMainOptions();
                $groupes      = $this->generator->getGroupeOptions();

                $sheet->setOrientation('landscape');
                $sheet->loadView('export.inscription', ['inscriptions' => $inscriptions, 'colloque' => $colloque, 'order' => $order, 'options' => $options, 'groupes' => $groupes]);

            });

        })->export('xls');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     *
     * @return Response
     */
    public function user()
    {
        $professions     = $this->profession->getAll();
        $cantons         = $this->canton->getAll();
        $pays            = $this->pays->getAll();
        $members         = $this->member->getAll();
        $specialisations = $this->specialisation->getAll();

        return view('export.user')->with(compact('pays','cantons','professions','members','specialisations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
