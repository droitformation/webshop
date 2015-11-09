<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Worker\InscriptionWorker;
use App\Droit\Adresse\Repo\AdresseInterface;

class ExportController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $worker;
    protected $adresse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ColloqueInterface $colloque,
        InscriptionInterface $inscription,
        InscriptionWorker $worker,
        AdresseInterface $adresse
    )
    {
        $this->inscription    = $inscription;
        $this->colloque       = $colloque;
        $this->worker         = $worker;
        $this->adresse        = $adresse;

        $this->generator = new \App\Droit\Generate\Excel\ExcelGenerator();
        $this->helper    = new \App\Droit\Helper\Helper();
        $this->label     = new \App\Droit\Helper\Label();
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
     *
     * @return Response
     */
    public function user()
    {
        return view('export.user');
    }

    /**
     * Search user global
     *
     * @return Response
     */
    public function globalexport(Request $request)
    {
        $each     = $request->input('each',false);
        $adresses = $this->adresse->searchMultiple($request->all(), $each);
        $terms    = $this->label->nameTerms($request->all());

        return view('export.results')->with(['adresses' => $adresses, 'terms' => $terms]);
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
}
