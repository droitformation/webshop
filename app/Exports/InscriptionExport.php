<?php namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class InscriptionExport implements FromView, WithColumnFormatting
{
    protected $inscriptions;
    protected $colloque;
    protected $headers;
    protected $columns;

    protected $sort;
    protected $dispatch;
    protected $occurrence;

    public $sorted;

    public function __construct($inscriptions, $colloque, $filters)
    {
        $this->inscriptions = $inscriptions;
        $this->colloque = $colloque;

        $this->columns = isset($filters['columns']) ? $filters['columns'] : config('columns.names');
        $this->headers = ['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $this->columns;

        $this->sort = isset($filters['sort']) ? $filters['sort'] : null;
        $this->dispatch = isset($filters['dispatch']) ? $filters['dispatch'] : null;
        $this->occurrence = isset($filters['occurrence']) ? $filters['occurrence'] : null;
    }

    public function view(): View
    {
        $sorted = $this->prepareInscription();

        return view('backend.export.inscriptions', [
            'inscriptions' => $sorted,
            'sort'         => $this->sort,
            'columns'      => $this->columns,
            'headers'      => $this->headers,
            'colloque'     => $this->colloque
        ]);
    }

    public function prepareInscription()
    {
        $sorted = $this->inscriptions->mapWithKeys(function ($occurence, $name){

            // All inscription are formatted
            $formatted = $occurence->map(function ($inscription){
                return $this->formatInscription($inscription);
            });

            $this->sorted = [];

            if($this->sort) {
                foreach($formatted as $inscription){
                    // Sort each person in each options
                    $depth  = $this->sort == 'choice' ? 2 : 1;
                    $filter = $this->sort == 'choice' ? $inscription['filter_choices'] : $inscription['filter_checkboxes'];

                    $this->sortByOption($filter, $inscription, $depth);
                }
            }
            else{
                $this->sorted[] = $formatted;
            }

            return [$name => $this->sorted];
        });

        return $sorted;
    }

    public function formatInscription($inscription)
    {
        $data = [];
        $user = $inscription->inscrit;

        $montant = floatval(str_replace(",",".",$inscription->price_cents));

        setlocale(LC_NUMERIC, 'en_US');

        $data['present']     = $inscription->present ? 'Oui' : '';
        $data['number']      = $inscription->inscription_no;
        $data['prix']        = $montant > 0 ? $montant : 0;
        $data['status']      = $inscription->status_name['status'];
        $data['date']        = $inscription->created_at->format('m/d/Y');
        $data['participant'] = $inscription->group_id > 0 ? $inscription->participant->name : '';

        // Adresse columns
        $data += ($user && isset($user->primary_adresse)) ? collect($this->columns)->map(function ($item, $key) use ($user) {
            return $user->primary_adresse->$key;
        })->toArray() : ['Le compte a été supprimé!'];

        // String with the options
        $data['checkbox_options'] = $inscription->export_checkbox_html;
        $data['choix_options']    = $inscription->export_choix_html;

        $data['filter_choices'] = !$inscription->user_options->isEmpty() ? $inscription->user_options->filter(function ($option, $key) {
            return in_array($option->option_id, array_keys($this->colloque->export_option_choix)) ? true : false;
        })->toArray() : [];

        $data['filter_checkboxes'] = !$inscription->user_options->isEmpty() ? $inscription->user_options->filter(function ($option, $key) {
            return in_array($option->option_id, array_keys($this->colloque->export_option_checkbox)) ? true : false;
        })->toArray() : [];

        return $data;
    }

    public function sortByOption($filter, $data, $depth = 2)
    {
        if(empty($filter)){$this->sorted[0][] = $data;}

        // Sort each person in each options
        array_walk($filter, function (&$value,$key) use ($data,$depth) {
            if($depth == 1){
                if(isset($data['checkbox_options'])){
                    unset($data['checkbox_options']);
                }
                $this->sorted[$value['option_id']][] = $data;
            }
            if($depth == 2){
                if(isset($data['choix_options'])){
                    unset($data['choix_options']);
                }
                $this->sorted[$value['option_id']][$value['groupe_id']][] = $data;
            }
        });
    }

    public function columnFormats(): array
    {
        return [
            //'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}