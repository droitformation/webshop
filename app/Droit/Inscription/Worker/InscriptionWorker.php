<?php
namespace App\Droit\Inscription\Worker;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Inscription\Repo\GroupeInterface;
use Illuminate\Support\Collection;

class InscriptionWorker implements InscriptionWorkerInterface{

    /*
    * Helper class for misc functions
    **/
    protected $helper;

    protected $inscription;
    protected $colloque;
    protected $option;
    protected $group;

    public $dispatch = [];

    public function __construct(InscriptionInterface $inscription, ColloqueInterface $colloque, OptionInterface $option, GroupeInterface $group)
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->option      = $option;
        $this->group       = $group;

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    public function register($data,$colloque_id, $simple = false)
    {
        if($simple)
        {
            $already = $this->inscription->getByUser($colloque_id,$data['user_id']);

            if($already)
            {
                throw new \App\Exceptions\RegisterException('Register failed');
            }
        }

        $inscription_no = $this->colloque->getNewNoInscription($colloque_id);

        // Prepare data
        $data        = $data + ['inscription_no' => $inscription_no];
        $inscription = $this->inscription->create($data);

        // Update counter
        $this->colloque->increment($colloque_id);

        return $inscription;
    }

    public function registerGroup($colloque, $request)
    {
        // create new group
        $group = $this->group->create(['colloque_id' => $colloque , 'user_id' => $request->input('user_id')]);

        // Get all infos for inscriptions/participants
        $participants = $request->input('participant');
        $prices       = $request->input('price_id');
        $options      = $request->input('options');
        $groupes      = $request->input('groupes');

        // Make inscription for each participant
        foreach($participants as $index => $participant)
        {
            $data = [
                'group_id'    => $group->id,
                'colloque_id' => $colloque,
                'participant' => $participant,
                'price_id'    => $prices[$index]
            ];

            // choosen options for participants
            if(isset($options[$index]))
            {
                $data['options'] = $options[$index];
            }

            // choosen groupe of options for participants
            if(isset($groupes[$index]))
            {
                $data['groupes'] = $groupes[$index];
            }

            // Register a new inscription
            $this->register($data,$colloque);

        }

        return $group;
    }

    public function sendEmail($model, $email)
    {
        // Update documents if they don't exist
        $this->makeDocuments($model);
        // Send prepared data and documents, update inscription with send date for admin
        $this->send($this->prepareData($model), $model->user, $model->documents, $email);
        $this->updateInscription($model);

        return true;
    }

    public function send($data, $user, $attachements, $email)
    {
        \Mail::send('emails.colloque.confirmation', $data , function ($message) use ($user,$attachements,$email) {

            // Overwrite the email to send to?
            $email = ($email ? $email : $user->email);

            $message->to($email, $user->name)->subject('Confirmation d\'inscription');

            if(!empty($attachements))
            {
                // Attach all documents
                foreach($attachements as $attachement)
                {
                    $message->attach($attachement['file'], ['as' => $attachement['name'], 'mime' => 'application/pdf']);
                }
            }
        });
    }

    public function prepareData($model)
    {
        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        if($model instanceof \App\Droit\Inscription\Entities\Groupe)
        {
            $data['annexes']      = $model->colloque->annexe;
            $data['colloque']     = $model->colloque;
            $data['user']         = $model->user;
            $data['participants'] = $model->participant_list;
        }

        if($model instanceof \App\Droit\Inscription\Entities\Inscription)
        {
            $data['annexes']  = $model->colloque->annexe;
            $data['colloque'] = $model->colloque;
            $data['user']     = $model->user;
        }

        return $data;
    }

    public function updateInscription($model)
    {
        // Update the send date and add true if send via admin
        if($model instanceof \App\Droit\Inscription\Entities\Groupe)
        {
            foreach($model->inscriptions as $inscription)
            {
                $this->inscription->update(['id' => $inscription->id, 'send_at' => date('Y-m-d'), 'admin' => 1]);
            }
        }
        else
        {
            $this->inscription->update(['id' => $model->id, 'send_at' => date('Y-m-d'), 'admin' => 1]);
        }
    }

    public function makeDocuments($model)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $annexes   = $model->colloque->annexe;

        // Generate annexes if any
        if(empty($model->documents) && !empty($annexes))
        {
            // Update the send date and add true if send via admin
            if($model instanceof \App\Droit\Inscription\Entities\Groupe)
            {
                foreach($model->inscriptions as $inscription)
                {
                    foreach($annexes as $annexe)
                    {
                        // Make the bon and the other docs if the price is not 0
                        if($annexe == 'bon' || ($model->price > 0 && ($annexe == 'facture' || $annexe == 'bv')))
                        {
                            $generator->make($annexe, $inscription);
                        }
                    }
                }
            }
            else
            {
                foreach($annexes as $annexe)
                {
                    // Make the bon and the other docs if the price is not 0
                    if($annexe == 'bon' || ($model->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')))
                    {
                        $generator->make($annexe, $model);
                    }
                }
            }
        }
    }

}