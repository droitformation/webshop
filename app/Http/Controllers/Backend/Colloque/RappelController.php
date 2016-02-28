<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\RappelInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Repo\GroupeInterface;
use Omnipay\Stripe\Message\Response;

class RappelController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $group;
    protected $rappel;
    protected $generator;

    public function __construct(InscriptionInterface $inscription, RappelInterface $rappel, ColloqueInterface $colloque, GroupeInterface $group)
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->group       = $group;
        $this->rappel      = $rappel;

        $this->generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    }

    /**
     * Rappels list
     * By colloque: colloque_id, type (simple or multiple), paginate
     * @param  $id
     * @return Response
     */
    public function rappels($id)
    {
        $colloque     = $this->colloque->find($id);
        $inscriptions = $this->inscription->getRappels($id);

        return view('backend.rappels.index')->with(['inscriptions' => $inscriptions, 'colloque' => $colloque]);
    }

    public function make($id)
    {
        $inscriptions = $this->inscription->getRappels($id);

        if(!$inscriptions->isEmpty())
        {
            $simple = $inscriptions->filter(function ($value, $key) {
                return !$value->group_id;
            });

            $group = $inscriptions->filter(function ($value, $key) {
                return $value->group_id;
            });

            foreach($simple as $inscription)
            {
                $this->generateSimple($inscription);
            }
        }

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Les rappels ont été crées'));
    }

    public function store(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        $this->generateSimple($inscription);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Le rappel a été crée'));
    }

    public function generateSimple($inscription)
    {
        $rappel = $this->rappel->create([
            'inscription_id' => $inscription->id,
            'user_id'        => $inscription->user_id,
            'group_id'       => $inscription->group_id,
        ]);

        $nbr = $inscription->rappels->count() + 1;

        $this->generator->setInscription($inscription);
        $this->generator->factureEvent($nbr, $rappel);

        return true;
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
