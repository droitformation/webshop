<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConferenceController extends Controller
{
    public function index()
    {
        $academiques = \Registry::get('academiques');
        $conference  = \Registry::get('conference');

        $count  = isset($academiques) && !empty($academiques) ? count($academiques) : 0;
        $isOpen = \Registry::get('conference') !== null && $count < $conference['places'] ? true : false;

        return view('frontend.conferences')->with(['isOpen' => $isOpen]);
    }

    public function store(Request $request)
    {
        $academiques = \Registry::get('academiques');

        $count = collect()->count($academiques);

        // Email already exist
        if(collect($academiques)->contains('email', $request->input('email'))){
            flash('Vous êtes déjà inscrit à l\'évenement avec cet email')->error();
            return redirect()->back();
        }

        // Add to array
        $academiques[] = $request->only(['first_name','last_name','email']);

        \Registry::store(['academiques' => $academiques]);

        $academiques = \Registry::get('academiques');

        flash('Inscription ajoutée')->success();

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $academiques = \Registry::get('academiques');

        // if array at index exist, unset it
        if(isset($academiques[$request->input('index')])){
            unset($academiques[$request->input('index')]);
            \Registry::store(['academiques' => $academiques]);
        }

        flash('Inscription supprimée')->success();

        return redirect()->back();
    }
}
