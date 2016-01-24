<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
                $sheet->loadView('backend.export.inscription', ['inscriptions' => $inscriptions, 'colloque' => $colloque, 'order' => $order, 'options' => $options, 'groupes' => $groupes]);

            });

        })->export('xls');
    }

    /**
     *
     * @return Response
     */
    public function view(Request $request)
    {
        $request->session()->forget('terms');
        $request->session()->forget('download');
        $request->session()->forget('count');

        return view('backend.export.user');
    }

    /**
     * Search user global
     *
     * @return Response
     */
    public function search(Request $request)
    {
        if($request->session()->has('terms'))
        {
            $terms = $request->session()->get('terms');
            $each  = (isset($terms['each']) ? true : false);
        }
        else
        {
            $terms = $request->all();
            $each  = $request->input('each',false);
            $request->session()->put('terms', $terms);
        }

        $adresses = $this->adresse->searchMultiple($terms, $each, 20);

        //$toExport = $this->adresse->searchMultiple($request->all(), $each);
        //$download = $this->doExport($toExport);

        $terms = $this->label->nameTerms($terms);
        $count = $adresses->total();

        return view('backend.export.results')->with(['adresses' => $adresses, 'terms' => $terms, 'download' => '', 'count' => $count]);
    }

    public function generate(Request $request)
    {
        $terms    = $request->session()->get('terms');
        $each     = (isset($terms['each']) ? true : false);
        $adresses = $this->adresse->searchMultiple($terms, $each);

        $this->doExport($adresses);

    }

    public function doExport($adresses, $store = false)
    {
        $export = \Excel::create('Export_Adresses_'.date('dmy'), function($excel) use ($adresses)
        {
            $excel->sheet('Export_Adresses', function($sheet) use ($adresses)
            {
                $columns = ['civilite_title','name','email','profession_title','company','telephone','mobile','adresse','cp','complement','npa','ville','canton_title','pays_title'];

                $sheet->setOrientation('landscape');
                $sheet->loadView('backend.export.adresse', ['adresses' => $adresses, 'columns' => $columns]);
            });
        });

        if($store)
        {
            $export->store('xls', storage_path('excel/exports'));
        }
        else{
            $export->download('xls');
        }

        //return 'Export_Adresses_'.date('dmy').'.xls';
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
