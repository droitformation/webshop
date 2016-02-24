<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\RappelInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;

class RappelController extends Controller
{
    protected $inscription;
    protected $rappel;
    protected $generator;

    public function __construct(InscriptionInterface $inscription, RappelInterface $rappel)
    {
        $this->inscription = $inscription;
        $this->rappel      = $rappel;

        $this->generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    }

    public function store(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        $rappel = $this->rappel->create([
            'inscription_id' => $inscription->id,
            'user_id'        => $inscription->user_id,
        ]);

        $rappels = $inscription->rappels->count();

        $this->generator->factureEvent($rappels);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Le rappel a été crée'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rappel->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Rappel supprimée']);
    }
}
