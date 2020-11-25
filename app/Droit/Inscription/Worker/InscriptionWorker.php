<?php
namespace App\Droit\Inscription\Worker;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Inscription\Repo\GroupeInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

class InscriptionWorker implements InscriptionWorkerInterface{
    
    protected $inscription;
    protected $colloque;
    protected $group;
    protected $generator;

    public function __construct(InscriptionInterface $inscription, ColloqueInterface $colloque, GroupeInterface $group, PdfGeneratorInterface $generator)
    {
        $this->inscription = $inscription;
        $this->group       = $group;
        $this->colloque    = $colloque;
        $this->generator   = $generator;
    }

    public function register($data, $simple = false)
    {
        if($simple) {
            return $this->inscription($data);
        }

        $colloque_id = \Arr::pull($data, 'colloque_id');
        $user_id     = \Arr::pull($data, 'user_id');

        // create new group
        $group = $this->group->create(['colloque_id' => $colloque_id, 'user_id' => $user_id]);

        collect($data['participant'])->map(function ($register,$key) use ($data) {
            return array_filter([
                'participant' => $data['participant'][$key],
                'email'       => $data['email'][$key],
                'price_id'    => isset($data['price_id'][$key]) ? $data['price_id'][$key] : null,
                'rabais_id'   => isset($data['rabais_id'][$key]) ? $data['rabais_id'][$key] : null,
                'occurrences' => isset($data['occurrences'][$key]) ? $data['occurrences'][$key] : null,
                'options'     => isset($data['options'][$key]) ? $data['options'][$key] : null,
                'groupes'     => isset($data['groupes'][$key]) ? $data['groupes'][$key] : null,
            ]);
        })->each(function ($item) use ($group) {
            $data = ['group_id'=> $group->id, 'colloque_id' => $group->colloque_id] + $item;

            $this->inscription($data);
        });

        // Attach references if any
        $reference = \App\Droit\Transaction\Reference::make($group);

        return $group;
    }

    public function inscription($data)
    {
        $inscription_no = $this->colloque->getNewNoInscription($data['colloque_id']);

        // Prepare data
        $data = $data + ['inscription_no' => $inscription_no];

        // Register
        $inscription = $this->inscription->create($data);

        // Attach references if any
        $reference = \App\Droit\Transaction\Reference::make($inscription);

        // Attach specialisations
        $user = ($inscription->group_id > 0 ? $inscription->groupe->user : $inscription->user);

        $this->specialisation($inscription->colloque, $user);

        return $inscription;
    }

    public function specialisation($colloque, $user)
    {
        if(!$colloque->specialisations->isEmpty())
        {
            $all = array_merge($colloque->specialisations->pluck('id')->all(), $user->adresse_contact->specialisations->pluck('id')->all());

            $user->adresse_contact->specialisations()->sync(array_unique($all));
        }
    }

    public function sendEmail($model, $email)
    {
        // Update documents if they don't exist
        $this->makeDocuments($model,true);

        // Send prepared data and documents, update inscription with send date for admin
        $attachements = $model->documents;

        // hold on the bon if we need to
        if(isset($attachements['bon']) && $model->colloque->keepBon){
            unset($attachements['bon']);
        }

        $this->send($this->prepareData($model, $attachements), $model->user, $attachements, $email);
        $this->updateInscription($model);

        return true;
    }

    public function send($data, $user, $attachements, $email)
    {
        if(substr(strrchr($email, "@"), 1) == 'publications-droit.ch'){
            throw new \App\Exceptions\EmailSubstituteException($email);
        }

        $email = ($email ? $email : $user->email);

        \Mail::to($email, $user->name)->bcc('archive@publications-droit.ch', 'Archive publications-droit')
            ->send(new \App\Mail\SendRegisterConfirmation($data['title'], $data['annexes'], $data['colloque'], $data['user'], $data['inscription'], $attachements));
    }

    public function prepareData($model, $attachements = [])
    {
        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            'annexes'      => $model->colloque->send_annexe,
            'colloque'     => $model->colloque,
            'user'         => $model->user,
            'attachements' => !empty($attachements) ? $attachements : $model->documents
        ];

        if($model instanceof \App\Droit\Inscription\Entities\Groupe)
        {
            $data['participants'] = $model->participant_list;
            $data['inscription']  = $model->inscriptions->first();
        }

        if($model instanceof \App\Droit\Inscription\Entities\Inscription)
        {
            $data['inscription']  = $model;
        }

        return $data;
    }

    public function updateInscription($model)
    {
        // List inscription ids to update
        $list = $model instanceof \App\Droit\Inscription\Entities\Groupe ? $model->inscriptions->pluck('id') : collect([$model->id]);

        // update all of them
        return $list->map(function ($id, $key) {
            return $this->inscription->updateSend(['id' => $id, 'send_at' => date('Y-m-d'), 'admin' => 1]);
        });
    }

    public function makeDocuments($model,$refresh = false)
    {
        $annexes = $model->colloque->annexe;

        // Force refresh of documents, only if we need to else we only test if there are no docs
        $refresh = $refresh ? true : empty($model->documents);

        // Generate annexes if any
        if($refresh && !empty($annexes)) {
            collect($annexes)->map(function ($annexe, $key) use ($model) {

                // List inscription to remake
                $list = $model instanceof \App\Droit\Inscription\Entities\Groupe ? $model->inscriptions : collect([$model]);

                if($annexe == 'bon') {
                    $list->map(function ($inscription, $key) {
                        $this->generator->make('bon', $inscription);
                    });
                }

                // Make the bon and the other docs if the price is not 0
                if($model->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')) {
                    $this->generator->make($annexe, $model);
                }

                // Backup the invoice/bv if the price is 0
                if($model->price_cents == 0) {
                    $this->backupDocuments($model);
                }
            });
        }
    }

    public function destroyDocuments($model)
    {
        \File::delete(collect($model->documents)->pluck('file')->all());
    }

    public function backupDocuments($model)
    {
        $docs = [$model->doc_bv,$model->doc_facture];

        collect(array_filter($docs))->map(function ($doc, $key) {
            \File::move(public_path($doc), public_path('files/colloques/bak/'.basename($doc)));
        });
    }
}