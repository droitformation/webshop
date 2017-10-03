<?php

namespace App\Droit\Sondage\Worker;


class SondageWorker
{
    protected $sondage;
    protected $list;
    protected $colloque_repo;

    public $colloque;

    public function __construct()
    {
        $this->sondage       = \App::make('App\Droit\Sondage\Repo\SondageInterface');
        $this->list          = \App::make('App\Droit\Newsletter\Repo\NewsletterListInterface');
        $this->colloque_repo = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    }

    public function createList($colloque_id)
    {
        $emails = $this->getEmails($colloque_id);

        if(empty($emails)){
            return false;
        }

        return $this->list->create(['title' => 'SONDAGE | '.$this->colloque->titre, 'emails' => $emails, 'colloque_id' => $this->colloque->id]);
    }

    public function updateList($colloque_id)
    {
        $list   = $this->list->findByColloque($colloque_id);
        $emails = $this->getEmails($colloque_id);

        if(empty($emails)){
            return false;
        }

        return $this->list->update(['id' => $list->id, 'title' => 'SONDAGE | '.$this->colloque->titre, 'emails' => $emails, 'colloque_id' => $this->colloque->id]);
    }

    public function getEmails($colloque_id)
    {
        $this->colloque = $this->colloque_repo->find($colloque_id);

        return $this->colloque->inscriptions->map(function ($inscription) {
            return $inscription->detenteur['email'];
        })->unique()->reject(function ($value, $key) {
            return !filter_var($value, FILTER_VALIDATE_EMAIL) || empty($value);
        })->toArray();
    }
}