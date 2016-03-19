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
    protected $badges;

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
        $this->inscription = $inscription;
        $this->colloque = $colloque;
        $this->worker = $worker;
        $this->adresse = $adresse;

        $this->generator = new \App\Droit\Generate\Excel\ExcelGenerator();
        $this->helper = new \App\Droit\Helper\Helper();
        $this->label = new \App\Droit\Helper\Label();

        $this->badges = config('badge');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function inscription(Request $request)
    {
        $names = $request->input('columns', config('columns.names'));
        $sort = $request->input('sort', false);
        $id = $request->input('id');

        \Excel::create('Export inscriptions', function ($excel) use ($id, $sort, $names) {

            $excel->sheet('Export', function ($sheet) use ($id, $sort, $names) {
                $sheet->setOrientation('landscape');

                $colloque = $this->colloque->find($id);
                $options = $colloque->options->whereLoose('type', 'choix')->pluck('title', 'id')->toArray();
                $groupes = $colloque->groupes->pluck('text', 'id')->toArray();

                $inscriptions = $this->inscription->getByColloque($id);

                if (!$inscriptions->isEmpty()) {
                    foreach ($inscriptions as $inscription) {
                        $user = $inscription->inscrit;

                        $data['Present'] = $inscription->present ? 'Oui' : '';
                        $data['Numéro'] = $inscription->inscription_no;
                        $data['Prix'] = $inscription->price_cents;
                        $data['Status'] = $inscription->status_name['status'];
                        $data['Date'] = $inscription->created_at->format('m/d/Y');
                        $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

                        // Adresse
                        if ($user && !$user->adresses->isEmpty()) {
                            foreach ($names as $column => $title) {
                                $data[$title] = $user->adresses->first()->$column;
                            }
                        }

                        if (!$inscription->user_options->isEmpty()) {
                            $html = '';
                            $groupe_choix = $inscription->user_options->whereLoose('groupe_id', null);

                            foreach ($groupe_choix as $choix) {
                                $html .= $choix->option->title . PHP_EOL;
                            }

                            $data['checkbox'] = $html;
                        }

                        if ($sort && !empty($groupes)) {
                            $user_options = $inscription->user_options->toArray();

                            foreach ($user_options as $option) {
                                if (in_array($option['option_id'], array_keys($options))) {
                                    $converted[$option['option_id']][$option['groupe_id']][] = $data;
                                }
                            }
                        } else {
                            if (!$inscription->user_options->isEmpty()) {
                                $html = '';
                                $groupe_choix = $inscription->user_options->groupBy('option_id');

                                foreach ($groupe_choix as $type) {
                                    foreach ($type as $choix) {
                                        $html .= $choix->option->title;
                                        $html .= $choix->groupe_id ? ':' : ';';
                                        $html .= ($choix->groupe_id ? $choix->option_groupe->text : '');
                                    }
                                }

                                $data['checkbox'] = $html;
                            }

                            $converted[] = $data;
                        }
                    }
                }

                $names['option_title'] = 'Choix';

                if ($sort && !empty($groupes)) {
                    foreach ($converted as $option_id => $option) {
                        $sheet->appendRow(['Options', $options[$option_id]]);

                        $sheet->row($sheet->getHighestRow(), function ($row) {
                            $row->setFontWeight('bold');
                            $row->setFontSize(16);
                        });
                        $sheet->appendRow(['']);

                        foreach ($option as $group_id => $group) {
                            $sheet->appendRow(['']);
                            $sheet->appendRow(['Choix', $groupes[$group_id]]);
                            $sheet->row($sheet->getHighestRow(), function ($row) {
                                $row->setFontWeight('bold');
                                $row->setFontSize(14);
                            });

                            $sheet->appendRow(['']);

                            $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $names);
                            $sheet->row($sheet->getHighestRow(), function ($row) {
                                $row->setFontWeight('bold');
                                $row->setFontSize(14);
                            });
                            $sheet->rows($group);
                        }
                    }
                } else {
                    $sheet->appendRow(['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $names);
                    $sheet->row($sheet->getHighestRow(), function ($row) {
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
        if ($request->session()->has('terms')) {
            $terms = $request->session()->get('terms');
            $each = (isset($terms['each']) ? true : false);
        } else {
            $terms = $request->all();
            $each = $request->input('each', false);
            $request->session()->put('terms', $terms);
        }

        $adresses = $this->adresse->searchMultiple($terms, $each, 20);

        $terms = $this->label->nameTerms($terms);
        $count = $adresses->total();

        return view('backend.export.results')->with(['adresses' => $adresses, 'terms' => $terms, 'download' => '', 'count' => $count]);
    }

    public function generate(Request $request)
    {
        $terms = $request->session()->get('terms');
        $each = (isset($terms['each']) ? true : false);
        $adresses = $this->adresse->searchMultiple($terms, $each);

        $this->doExport($adresses);

    }

    public function doExport($adresses, $store = false)
    {
        $export = \Excel::create('Export_Adresses_' . date('dmy'), function ($excel) use ($adresses) {
            $excel->sheet('Export_Adresses', function ($sheet) use ($adresses) {
                $columns = ['civilite_title', 'name', 'email', 'profession_title', 'company', 'telephone', 'mobile', 'adresse', 'cp', 'complement', 'npa', 'ville', 'canton_title', 'pays_title'];

                $converted = $adresses->map(function ($adresse) use ($columns) {
                    $convert = new \App\Droit\Helper\Convert();

                    foreach ($columns as $column) {
                        $convert->setAttribute($column, $adresse->$column);
                    }

                    return $convert;
                });

                $sheet->appendRow(['Civilité', 'Nom', 'Email', 'Profession', 'Entreprise', 'Téléphone', 'Mobile', 'Adresse', 'CP', 'Complèment', 'NPA', 'Ville', 'Canton', 'Pays']);
                $sheet->row($sheet->getHighestRow(), function ($row) {
                    $row->setFontWeight('bold');
                    $row->setFontSize(14);
                });
                $sheet->rows($converted->toArray());

            });
        });

        if ($store) {
            $export->store('xls', storage_path('excel/exports'));
        } else {
            $export->download('xls');
        }
    }

    public function badges(Request $request)
    {
        $colloque_id = $request->input('colloque_id');
        $format      = $request->input('format');
        $format      = explode('|', $format);

        $badge = $this->badges[$format[1]];

        $inscriptions = $this->inscription->getByColloque($colloque_id,false,false);
        $inscriptions = $inscriptions->pluck('inscrit.name')->all();
        $data         = $this->chunkData($inscriptions, $badge['cols'], $badge['etiquettes']);

        $configuration = $badge + ['data' => $data];

        return \PDF::loadView('backend.export.badge', $configuration)->setPaper('a4')->stream('badges_' . $colloque_id . '.pdf');
    }

    public function chunkData($data,$cols,$nbr)
    {
        if(!empty($data))
        {
            $chunks = array_chunk($data,$cols);
            $chunks = array_chunk($chunks,$nbr/$cols);

            return $chunks;
        }
        return [];
    }
}