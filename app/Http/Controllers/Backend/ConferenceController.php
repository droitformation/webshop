<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConferenceController extends Controller
{
    public function index()
    {
        return view('backend.conferences.index');
    }

    public function store(Request $request)
    {
        \Registry::store($request->except('_token'));

        flash('Informations enregistrées')->success();

        return redirect()->back();
    }

    public function export()
    {
        $academiques = \Registry::get('academiques');
        $conference  = \Registry::get('conference');

        return \Excel::download(new \App\Exports\DejeunerExport($academiques,$conference), 'inscriptions_dejeuner_academiques_' . date('dmy').'.xlsx');
    }
}
