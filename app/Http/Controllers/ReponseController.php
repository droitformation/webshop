<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\ReponseInterface;
use App\Droit\Sondage\Repo\SondageInterface;

class ReponseController extends Controller
{
    protected $reponse;
    protected $sondage;

    public function __construct(ReponseInterface $reponse, SondageInterface $sondage)
    {
        $this->reponse = $reponse;
        $this->sondage = $sondage;
    }

    public function create($token)
    {
        $data = json_decode(base64_decode($token));

        $data = [
            'sondage_id' => 1,
            'email'      => 'cindy.leschaud@gmail.com',
            'isTest'     => 1,
        ];

        $sondage = $this->sondage->find($data['sondage_id']);

        return view('sondages.index')->with(['sondage' => $sondage, 'email' => $data['email'], 'isTest' => $data['isTest']]);
    }

    public function store(Request $request)
    {
        $reponse = $this->reponse->create($request->all());

        alert()->success('La question a été crée');

        return redirect('admin/avis');
    }
}
