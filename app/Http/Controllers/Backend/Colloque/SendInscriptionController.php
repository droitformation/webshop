<?php

namespace App\Http\Controllers\Backend\Colloque;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Worker\InscriptionWorkerInterface;
use App\Droit\Inscription\Repo\GroupeInterface;

use App\Http\Requests;
use App\Http\Requests\SendAdminInscriptionRequest;
use App\Http\Controllers\Controller;

class SendInscriptionController extends Controller
{
    protected $inscription;
    protected $register;
    protected $group;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InscriptionInterface $inscription, InscriptionWorkerInterface $register, GroupeInterface $group)
    {
        $this->inscription = $inscription;
        $this->register    = $register;
        $this->group       = $group;
    }

    /**
     * Send inscription via admin
     *
     * @return Response
     */
    public function send(SendAdminInscriptionRequest $request)
    {
        $model = $request->input('model');

        // Find model inscription or group
        $item = $this->$model->find($request->input('id'));

        if(!$item) {
            throw new \App\Exceptions\InscriptionExistException('Aucune inscription ou groupe trouvé!');
        }

        $this->register->sendEmail($item, $request->input('email'));

        alert()->success('Email envoyé');

        return redirect()->back();
    }
}
