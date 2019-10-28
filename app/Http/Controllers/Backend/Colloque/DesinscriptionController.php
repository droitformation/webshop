<?php

namespace App\Http\Controllers\Backend\Colloque;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Inscription\Repo\GroupeInterface;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\InscriptionCreateRequest;
use App\Http\Requests\MakeInscriptionRequest;
use App\Http\Requests\SendAdminInscriptionRequest;
use App\Http\Controllers\Controller;

class DesinscriptionController extends Controller
{
    protected $inscription;
    protected $register;
    protected $colloque;
    protected $user;
    protected $generator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, UserInterface $user, InscriptionWorkerInterface $register, GroupeInterface $groupe)
    {
        $this->colloque    = $colloque;
        $this->inscription = $inscription;
        $this->register    = $register;
        $this->user        = $user;
        $this->groupe      = $groupe;

        $this->generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->helper      = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $colloque        = $this->colloque->find($id);
        $desinscriptions = $this->inscription->getByColloqueTrashed($id);

        $desinscriptions = $desinscriptions->filter(function ($inscription, $key) {
            $display = new \App\Droit\Inscription\Entities\Display($inscription);
            return $display->isValid();
        });

        return view('backend.inscriptions.desinscription')->with(['colloque' => $colloque, 'desinscriptions' => $desinscriptions]);
    }

    /**
     * Restore the inscription
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $inscription = $this->inscription->restore($id);

        // Remake the documents
        $model = ($inscription->group_id ? $inscription->groupe : $inscription);

        $this->register->makeDocuments($model, true);

        // If the inscription was in a group, restore the group
        if($inscription->group_id > 0 && $inscription->groupe) {
            $this->groupe->restore($inscription->group_id);
        }

        flash('L\'inscription a été restauré')->success();

        return redirect()->back();
    }
}
