<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\SondageInterface;

class SondageAvisController extends Controller
{
    protected $sondage;

    public function __construct(SondageInterface $sondage)
    {
        $this->sondage  = $sondage;
    }

    public function store(Request $request)
    {
        $sondage = $this->sondage->find($request->input('id'));
        $max = $sondage->avis->max('pivot.rang') ? $sondage->avis->max('pivot.rang') : 0;

        // multiple questions add
        $avis = collect($request->input('question_id'))->map(function ($item, $key) use ($max, $sondage) {
            $sondage->avis()->attach($item, ['rang' => ($max + 1) + $key]);
        });

        alert()->success('Question ajouté');

        return redirect()->back();
    }
    
    public function destroy($id,Request $request)
    {
        $sondage = $this->sondage->find($id);

        $sondage->avis()->detach($request->input('question_id'));

        alert()->success('Question retirée');

        return redirect()->back();
    }
}
