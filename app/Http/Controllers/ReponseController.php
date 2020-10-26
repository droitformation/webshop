<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Sondage\Repo\SondageInterface;

use App\Droit\Sondage\Worker\ReponseWorker;

class ReponseController extends Controller
{
    protected $reponse;
    protected $sondage;
    protected $worker;

    public function __construct(ReponseInterface $reponse, SondageInterface $sondage, ReponseWorker $worker)
    {
        $this->reponse = $reponse;
        $this->sondage = $sondage;
        $this->worker  = $worker;
    }

    public function index($sondage_id = null)
    {
        $sondage = $sondage_id ? $this->sondage->find($sondage_id) : null;

        return view('sondages.index')->with(['sondage' => $sondage]);
    }

    public function create($token)
    {
        $data    = (array) json_decode(base64_decode($token));

        if($data['email'] == 'cristian.ferrara@bluewin.ch'){
            $data['email'] = 'sondage'.$data['sondage_id'].'_'.rand(20,10000).'@publications-droit.ch';
        }

        $sondage = $this->sondage->find($data['sondage_id']);

        if($data['email'] == 'cristian.ferrara@bluewin.ch'){
            $data['email'] = 'sondage'.$data['sondage_id'].'_'.rand(20,10000).'@publications-droit.ch';
        }

        $answer  = $this->reponse->hasAnswer($data['email'], $data['sondage_id']);
        $isTest  = isset($data['isTest']) ? true : false;

        if($answer) {
            flash('Vous avez déjà répondu au sondage!')->warning();

            return redirect('reponse');
        }

        return view('sondages.create')->with(['sondage' => $sondage, 'email' => $data['email'], 'isTest' => $isTest]);
    }

    public function store(Request $request)
    {
        $reponse = $this->worker->make($request->except('reponses'), $request->only('reponses'));

        flash('Merci pour votre participation au sondage!')->success();

        return redirect('reponse/'.$reponse->sondage->id);
    }
}
