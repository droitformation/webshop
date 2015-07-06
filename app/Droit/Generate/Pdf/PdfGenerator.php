<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;

class PdfGenerator
{
    protected $generator;

    public function __construct()
    {

    }
    public function facture($id)
    {
        $data = [];

        return \PDF::loadView('shop.templates.facture', $data)->setPaper('a4')->stream('download.pdf');
    }

}