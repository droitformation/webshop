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
    public function inscription(Request $request)
    {
        $names = $request->input('columns',config('columns.names'));
        $sort  = $request->input('sort',false);
        $id    = $request->input('id');

        \Excel::create('Export inscriptions', function($excel) use ($id,$sort,$names) {

            $excel->sheet('Export', function($sheet) use ($id,$sort,$names) {
                $sheet->setOrientation('landscape');

                $colloque  = $this->colloque->find($id);
                $options   = $colloque->options->whereLoose('type','choix')->pluck('title','id')->toArray();
                $groupes   = $colloque->groupes->pluck('text','id')->toArray();

                $inscriptions = $this->inscription->getByColloque($id);

                if(!$inscriptions->isEmpty())
                {
                    foreach($inscriptions as $inscription)
                    {
                        $user = $inscription->inscrit;

                        $data['Numéro']      = $inscription->inscription_no;
                        $data['Prix']        = $inscription->price_cents;
                        $data['Status']      = $inscription->status_name['status'];
                        $data['Date']        = $inscription->created_at->format('m/d/Y');
                        $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

                        // Adresse
                        if($user && !$user->adresses->isEmpty())
                        {
                            foreach($names as $column => $title)
                            {
                                $data[$title] = $user->adresses->first()->$column;
                            }
                        }

                        if($sort && !empty($groupes))
                        {
                            $user_options = $inscription->user_options->toArray();

                            foreach($user_options as $option)
                            {
                                if(in_array( $option['option_id'],array_keys($options) ))
                                {
                                   $converted[$option['option_id']][$option['groupe_id']][] = $data;
                                }
                            }
                        }
                        else
                        {
                            $converted[] = $data;
                        }
                    }
                }

                if($sort && !empty($groupes))
                {
                    foreach($converted as $option_id => $option)
                    {
                        $sheet->appendRow([ 'Options', $options[$option_id] ]);

                        $sheet->row($sheet->getHighestRow(), function ($row) {
                            $row->setFontWeight('bold');
                            $row->setFontSize(16);
                        });
                        $sheet->appendRow(['']);

                        foreach($option as $group_id => $group)
                        {
                            $sheet->appendRow(['']);
                            $sheet->appendRow([ 'Choix', $groupes[$group_id] ]);
                            $sheet->row($sheet->getHighestRow(), function ($row) {
                                $row->setFontWeight('bold');
                                $row->setFontSize(14);
                            });

                            $sheet->appendRow(['']);

                            $sheet->appendRow(['Numéro','Prix','Status','Date','Participant'] + $names);
                            $sheet->row($sheet->getHighestRow(), function ($row) {
                                $row->setFontWeight('bold');
                                $row->setFontSize(14);
                            });
                            $sheet->rows($group);
                        }
                    }
                }
                else
                {
                    $sheet->appendRow(['Numéro','Prix','Status','Date','Participant'] + $names);
                    $sheet->row($sheet->getHighestRow(), function ($row)
                    {
                        $row->setFontWeight('bold');
                        $row->setFontSize(14);
                    });
                    $sheet->rows($converted);
                }

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

                $converted = $adresses->map(function($adresse) use ($columns)
                {
                    $convert = new \App\Droit\Helper\Convert();

                    foreach($columns as $column)
                    {
                        $convert->setAttribute($column,$adresse->$column);
                    }

                    return $convert;
                });

                $sheet->appendRow(['Civilité','Nom','Email','Profession','Entreprise','Téléphone','Mobile','Adresse','CP','Complèment','NPA','Ville','Canton','Pays']);
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontSize(14);
                });
                $sheet->rows($converted->toArray());

               // $sheet->setOrientation('landscape');
                //$sheet->loadView('backend.export.adresse', ['adresses' => $adresses, 'columns' => $columns]);
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
