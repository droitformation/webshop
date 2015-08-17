<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;

class PdfGenerator
{
    protected $order;
    protected $inscription;
    protected $user;

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
        $this->order       = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');;
        $this->user        = \App::make('App\Droit\User\Repo\UserInterface');;
        $this->inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');;
    }

    /*
     * Set messages
     * Type: warning,special,message,remerciements
     */
    public function setMsg($message,$type)
    {
        $this->messages[$type] = $message;
    }

    public function factureOrder($order_id, $stream = false)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date  = Carbon::now()->formatLocalized('%d %B %Y');

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
            'date'      => $date
        ];

        $facture = \PDF::loadView('shop.templates.facture', $data)->setPaper('a4');

        $generate = ($stream ? 'stream' : 'save');

        return $facture->$generate(public_path().'/files/shop/factures/facture_'.$order->order_no.'.pdf');

    }

    public function bonEvent($inscription,$stream = false){

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date  = Carbon::now()->formatLocalized('%d %B %Y');

        $inscription->load('user_options','groupe','participant');
        $inscription->user_options->load('option');
        $inscription->colloque->load('location','centres','compte');

        $user = $inscription->inscrit;
        $user->load('adresses');

        $inscription->setAttribute('adresse_facturation',$user->adresse_facturation);

        $data = [
            'expediteur'  => $this->expediteur,
            'inscription' => $inscription,
            'date'        => $date
        ];

        $bon = \PDF::loadView('colloques.templates.bon', $data)->setPaper('a4');

        $generate = ($stream ? 'stream' : 'save');

        return $bon->$generate(public_path().'/files/colloques/bon/bon_'.$inscription->colloque->id.'-'.$inscription->inscrit->id.'.pdf');

    }

    public function factureEvent($inscription,$stream = false){

        if($inscription->price->price > 0)
        {
            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $date  = Carbon::now()->formatLocalized('%d %B %Y');

            $inscription->load('user_options','groupe','participant');
            $inscription->user_options->load('option');
            $inscription->colloque->load('location','centres','compte');

            $user = $inscription->inscrit;
            $user->load('adresses');

            $inscription->setAttribute('adresse_facturation',$user->adresse_facturation);

            $data = [
                'messages'    => $this->messages,
                'expediteur'  => $this->expediteur,
                'inscription' => $inscription,
                'date'        => $date,
                'signature'   => $this->signature,
                'tva'         => $this->tva,
                'annexes'     => $inscription->annexe
            ];

            $facture = \PDF::loadView('colloques.templates.facture', $data)->setPaper('a4');

            $generate = ($stream ? 'stream' : 'save');

            return $facture->$generate(public_path().'/files/colloques/facture/facture_'.$inscription->colloque->id.'-'.$inscription->inscrit->id.'.pdf');
        }

        return true;
    }


    public function bvEvent($inscription, $stream = false)
    {

        if($inscription->price->price > 0)
        {
            $inscription->colloque->load('compte');

            $data = ['inscription' => $inscription,];

            $bv = \PDF::loadView('colloques.templates.bv', $data)->setPaper('a4');

            $generate = ($stream ? 'stream' : 'save');

            return $bv->$generate(public_path().'/files/colloques/bv/bv_'.$inscription->colloque->id.'-'.$inscription->inscrit->id.'.pdf');
        }

        return true;

    }

}