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

        return view('backend.rappels.index')->with(['inscriptions' => $inscriptions,'colloque' => $colloque]);
    }

    public function make($id)
    {
        $inscriptions = $this->inscription->getRappels($id);

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                // Simple rappels
                if($inscription->group_id)
                {
                    $this->generateMultiple($inscription->groupe);
                }
                else   // Multiple rappels
                {
                    $this->generateSimple($inscription);
                }
            }
        }

        alert()->success('Les rappels ont été crées');

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $inscription = $this->inscription->find($request->input('id'));

        if($inscription->group_id)
        {
            $this->generateMultiple($inscription->groupe);
        }
        else
        {
            $this->generateSimple($inscription);
        }

        alert()->success('Le rappel a été crée');

        return redirect()->back();
    }

    public function generateSimple($inscription)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $inscription->colloque_id,
            'inscription_id' => $inscription->id,
            'user_id'        => $inscription->user_id,
            'group_id'       => $inscription->group_id,
        ]);

        $this->generator->make('facture', $inscription, $rappel);

        return true;
    }

    public function generateMultiple($group)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $group->colloque_id,
            'inscription_id' => null,
            'user_id'        => $group->user_id,
            'group_id'       => $group->id,
        ]);

        $this->generator->make('facture', $group, $rappel);

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

        alert()->success('Rappel supprimé');

        return redirect()->back();
    }
}
