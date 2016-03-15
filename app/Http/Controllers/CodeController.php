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
     * Show the form for creating a new resource.
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
     * Show the form for creating a new resource.
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
