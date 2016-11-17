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

        if($sondage->avis->contains('id',$request->input('question_id')))
        {
            alert()->warning('La question existe déjà');
            return redirect()->back();
        }

        $sondage->avis()->attach($request->input('question_id'), ['rang' => $sondage->avis->max('pivot.rang')]);

        alert()->success('Question ajouté');

        return redirect()->back();
    }
    
    public function destroy($id,Request $request)
    {
        $sondage = $this->sondage->find($id);

        $sondage->avis()->detach($request->input('question_id'));

        return redirect()->back();
    }
}
