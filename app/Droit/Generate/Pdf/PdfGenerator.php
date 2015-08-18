<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;

class PdfGenerator
{
    protected $order;
    protected $inscription;
    protected $user;
    protected $now;

    public $stream = false;

    /**
     * Facture shop
     **/
    public $messages = ['remerciements' => 'Avec nos remerciements, nous vous adressons nos salutations les meilleures'];
    public $compte   = '20-4130-2';
    public $centre   = 'U. 01852';
    public $motif    = 'Vente ouvrages';

    public $expediteur = [
        'nom'     => 'Secr&eacute;tariat - Formation',
        'adresse' => 'Avenue du 1er-Mars 26',
        'ville'   => 'CH-2000 Neuch&acirc;tel'
    ];

    public $versement = [
        'nom'     => 'Université de Neuchâtel',
        'adresse' => 'Séminaire sur le droit du bail',
        'ville'   => '2000 Neuchâtel'
    ];

    public $tva = [
        'numero'      => 'CHE-115.251.043 TVA',
        'taux_réduit' => 'Taux 2.5% inclus pour les livres',
        'taux_normal' => 'Taux 8% pour les autres produits'
    ];

    public $signature = 'Le secrétariat de la Faculté de droit';

    public function __construct()
    {
        $this->order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->user  = \App::make('App\Droit\User\Repo\UserInterface');
        $this->now   = Carbon::now()->formatLocalized('%d %B %Y');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /*
     * Set messages
     * Type: warning,special,message,remerciements
     */
    public function setMsg($message,$type)
    {
        $this->messages[$type] = $message;
    }

    public function factureOrder($order_id)
    {
        $order = $this->order->find($order_id);
        $order->load('products','user','shipping','payement');
        $order->user->load('adresses');

        $products = $order->products->groupBy('id');
        $msgTypes = ['warning','special','remarque','signature'];

        $data = [
            'expediteur' => $this->expediteur,
            'messages'   => $this->messages,
            'versement'  => $this->versement,
            'motif' => [
                'centre' => $this->centre,
                'texte'  => $this->motif,
            ],
            'tva'       => $this->tva,
            'compte'    => $this->compte,
            'order'     => $order,
            'products'  => $products,
            'msgTypes'  => $msgTypes,
            'date'      => $this->now
        ];

        $facture = \PDF::loadView('shop.templates.facture', $data)->setPaper('a4');

        $generate = ($this->stream ? 'stream' : 'save');

        return $facture->$generate(public_path().'/files/shop/factures/facture_'.$order->order_no.'.pdf');

    }

    /* *
     * Inscriptions colloques
     * Prépare inscription with infos
     * */

    public function setInscription($inscription)
    {

        $inscription->load('user_options','groupe','participant');
        $inscription->user_options->load('option');
        $inscription->colloque->load('location','centres','compte');

        $user = $inscription->inscrit;
        $user->load('adresses');

        $inscription->setAttribute('adresse_facturation',$user->adresse_facturation);

        $this->inscription = $inscription;

        return $this;
    }

    public function bonEvent()
    {
        $data = [
            'expediteur'  => $this->expediteur,
            'inscription' => $this->inscription,
            'date'        => $this->now
        ];

        $bon = \PDF::loadView('colloques.templates.bon', $data)->setPaper('a4');

        $generate = ($this->stream ? 'stream' : 'save');

        $part = (isset($this->inscription->participant) ? '-'.$this->inscription->participant->id : '');

        return $bon->$generate(public_path().'/files/colloques/bon/bon_'.$this->inscription->colloque->id.'-'.$this->inscription->inscrit->id.$part.'.pdf');

    }

    public function factureEvent()
    {
        if($this->inscription->price->price > 0)
        {
            $data = [
                'messages'    => $this->messages,
                'expediteur'  => $this->expediteur,
                'inscription' => $this->inscription,
                'date'        => $this->now,
                'signature'   => $this->signature,
                'tva'         => $this->tva,
                'annexes'     => $this->inscription->annexe
            ];

            $facture  = \PDF::loadView('colloques.templates.facture', $data)->setPaper('a4');

            $generate = ($this->stream ? 'stream' : 'save');

            return $facture->$generate(public_path().'/files/colloques/facture/facture_'.$this->inscription->colloque->id.'-'.$this->inscription->inscrit->id.'.pdf');
        }

        return true;
    }


    public function bvEvent()
    {
        if($this->inscription->price->price > 0)
        {
            $data = ['inscription' => $this->inscription];

            $bv = \PDF::loadView('colloques.templates.bv', $data)->setPaper('a4');

            $generate = ($this->stream ? 'stream' : 'save');

            return $bv->$generate(public_path().'/files/colloques/bv/bv_'.$this->inscription->colloque->id.'-'.$this->inscription->inscrit->id.'.pdf');
        }

        return true;
    }

    public function generate($annexes)
    {
        $toGenereate = (count(array_intersect($annexes, ['bon','facture'])) == count(['bon','facture']) ? true : false);

        if($this->inscription->group_id && $toGenereate)
        {
            $this->bonEvent($this->inscription);
        }
        else
        {
            foreach($annexes as $annexe)
            {
                $doc = $annexe.'Event';
                $this->$doc($this->inscription);
            }
        }
    }

}