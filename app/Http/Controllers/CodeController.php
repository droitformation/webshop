<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Inscription\Repo\InscriptionInterface;

class CodeController extends Controller
{
    protected $inscription;

    public function __construct( InscriptionInterface $inscription)
    {
        $this->inscription = $inscription;
    }

    public function index()
    {
        return view('shop.code');
    }

    /**
     * Validate presence of inscrit for inscription
     *
     * @return \Illuminate\Http\Response
     */
    public function presence($id,$key)
    {
        $valid = config('services.qrcode.key');

        if($key != $valid){
            abort(403, 'Unauthorized action.');
        }

        $inscription = $this->inscription->find($id);

        if($inscription)
        {
            $inscription->present = 1;
            $inscription->save();
        }

        return view('auth.presence')->with(['status' => 'success' ,'message' => 'Présence validé!']);
    }

    /**
     * Validate presence of inscrit occurrence for inscription
     *
     * @return \Illuminate\Http\Response
     */
    public function occurrence($id,$key)
    {
        $valid = config('services.qrcode.key');

        if ($key != $valid) {
            abort(403, 'Unauthorized action.');
        }

        $inscription = $this->inscription->find($id);

        $today = \Carbon\Carbon::today();

        $presence = $inscription->occurrences->filter(function ($value, $key) use ($today) {
            return $value->start_at == $today;
        });

        if(!$presence->isEmpty())
        {
            $inscription->occurrences()->updateExistingPivot($presence->first()->id, ['present' => 1]);

            return view('auth.presence')->with(['status' => 'success' ,'message' => 'Présence validé!']);
        }

        return view('auth.presence')->with(['status' => 'danger' ,'message' => 'La personnes n\'est pas inscrite à cette conférence']);
    }

}
