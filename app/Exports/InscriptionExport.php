<?php namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InscriptionExport implements FromView
{
    protected $inscriptions;
    protected $headers;
    protected $columns;

    public function __construct($inscriptions, $columns)
    {
        $this->inscriptions = $inscriptions;
        $this->columns = $columns;
        $this->headers = ['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $columns;
    }

    public function view(): View
    {
        return view('backend.export.inscriptions', [
            'inscriptions' => $this->inscriptions,
            'columns' => $this->columns,
            'headers' => $this->headers
        ]);
    }
}