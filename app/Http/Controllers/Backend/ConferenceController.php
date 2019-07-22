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

        alert()->success('Informations enregistrées');

        return redirect()->back();
    }
}
