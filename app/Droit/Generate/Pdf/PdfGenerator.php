<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\User\Repo\UserInterface;

class PdfGenerator
{
    protected $order;

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

    public function __construct(OrderInterface $order, UserInterface $user)
    {
        $this->order  = $order;
        $this->user   = $user;
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

        if($stream)
        {
           return $facture->stream('facture_'.$order->order_no.'.pdf');
        }

        return $facture->save(public_path().'/files/shop/factures/facture_'.$order->order_no.'.pdf');

    }

    public function bonEvent($inscription_id){

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date  = Carbon::now()->formatLocalized('%d %B %Y');

        $user = $this->user->find(1);
        $user->load('adresses');

        $data = [
            'expediteur' => $this->expediteur,
            'logo'       => 'facdroit.jpg',
            'carte'      => 'carte.jpg',
            'colloque'   => [
                'organisateur' => 'Séminaire sur le droit du bail',
                'titre'        => 'Convergences et divergences entre le droit de la fonction publique et le droit privé du travail ?',
                'soustitre'    => 'Conférence-débat et Journée scientifique',
                'lieu'         => 'Aula des Jeunes-Rives, Espace Louis-Agassiz 1, Neuchâtel',
                'date'         => $date,
            ],
            'user' => $user,
            'inscription_no' => '64-2015/44'
        ];

        return \PDF::loadView('colloques.templates.bon', $data)->setPaper('a4')->stream('bon.pdf');

    }

    public function factureEvent($inscription_id){

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date  = Carbon::now()->formatLocalized('%d %B %Y');

        $user = $this->user->find(1);
        $user->load('adresses');

        $data = [
            'messages'   => $this->messages,
            'expediteur' => $this->expediteur,
            'colloque'   => [
                'organisateur' => 'Séminaire sur le droit du bail',
                'titre'        => 'Convergences et divergences entre le droit de la fonction publique et le droit privé du travail ?',
                'soustitre'    => 'Conférence-débat et Journée scientifique',
                'lieu'         => 'Aula des Jeunes-Rives, Espace Louis-Agassiz 1, Neuchâtel',
                'date'         => $date,
            ],
            'user'           => $user,
            'inscription_no' => '64-2015/44',
            'price'          => '290',
            'date'           => $date,
            'signature'      => $this->signature,
            'tva'            => $this->tva,
            'annexes'        => ['bon' => 'bon de participation à présenter à l\'entrée']
        ];

        return \PDF::loadView('colloques.templates.facture', $data)->setPaper('a4')->stream('bon.pdf');

    }


    public function bvEvent($inscription_id, $stream = false)
    {
        $price    = '290.00';
        $user     = $this->user->find(1);
        $colloque = 64;

        $data = [
            'versement'  => $this->versement,
            'motif' => [
                'centre' => 'Conférence-débat et Journée scientifique',
                'texte'  => '',
            ],
            'compte'    => $this->compte,
            'price'     => $price,
            'inscription_no' => '64-2015/44',
        ];

        $bv = \PDF::loadView('colloques.templates.bv', $data)->setPaper('a4');

        if($stream)
        {
            return $bv->stream('bv_'.$colloque.'-'.$user->id.'.pdf');
        }

        return $bv->save('files/colloques/factures/bv_'.$colloque.'-'.$user->id.'.pdf');

    }

}