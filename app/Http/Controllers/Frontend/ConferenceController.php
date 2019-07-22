<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConferenceController extends Controller
{
    public function index()
    {
        return view('frontend.conferences');
    }

    public function store(Request $request)
    {
        $academiques   = \Registry::get('academiques');

        if(collect($academiques)->contains('email', $request->input('email'))){
            alert()->danger('Vous êtes déjà inscrit à l\'évenement');
            return redirect()->back();
        }

        $academiques[] = [
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'email'      => $request->input('email'),
        ];

        \Registry::store(['academiques' => $academiques]);

        alert()->success('Inscription ajoutée');

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $academiques   = \Registry::get('academiques');

        if(isset($academiques[$request->input('index')])){
            unset($academiques[$request->input('index')]);
            \Registry::store(['academiques' => $academiques]);
        }

        alert()->success('Inscription supprimée');

        return redirect()->back();
    }
}
