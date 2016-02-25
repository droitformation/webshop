<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\User\Repo\UserInterface;

class PdfGenerator implements PdfGeneratorInterface
{
    protected $order;
    protected $user;
    protected $inscription;
    protected $now;

    public $stream = false;

    /**
     * Facture shop
     **/
    public $messages = ['remerciements' => 'Avec nos remerciements, nous vous adressons nos salutations les meilleures'];
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

    public $tva;

    public $signature = 'Le secrétariat de la Faculté de droit';

    public function __construct(OrderInterface $order, UserInterface $user)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->order = $order;
        $this->user  = $user;

        $this->now   = Carbon::now()->formatLocalized('%d %B %Y');

        $this->tva = [
            'numero'      => \Registry::get('shop.infos.tva'),
            'taux_reduit' => \Registry::get('shop.infos.taux_reduit'),
            'taux_normal' => \Registry::get('shop.infos.taux_normal')
        ];
    }

    /*
     * Set messages
     * Type: warning,special,message,remerciements
     */
    public function setMsg($message,$type)
    {
        $this->messages[$type] = $message;
    }

    /*
     * Set taux tva
     * Type: warning,special,message,remerciements
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    }

    public function factureOrder($order_id)
    {
        $order = $this->order->find($order_id);
        $order->load('products','user','shipping','payement');

        if($order->user_id)
        {
            $order->user->load('adresses');
            $adresse = $order->user->adresse_facturation;
        }
        else
        {
            $adresse = $order->adresse;
        }

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
            'tva' => [
                'taux_reduit' => 'Taux '.$this->tva['taux_reduit'].'% inclus pour les livres',
                'taux_normal' => 'Taux '.$this->tva['taux_normal'].'% pour les autres produits'
            ],
            'compte'    => \Registry::get('shop.compte.colloque'),
            'order'     => $order,
            'adresse'   => $adresse,
            'products'  => $products,
            'msgTypes'  => $msgTypes,
            'date'      => $this->now
        ];

        $facture = \PDF::loadView('templates.shop.facture', $data)->setPaper('a4');

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

        $bon = \PDF::loadView('templates.colloque.bon', $data)->setPaper('a4');

        $generate = ($this->stream ? 'stream' : 'save');

        $part = (isset($this->inscription->participant) ? $this->inscription->group_id.'-'.$this->inscription->participant->id : $this->inscription->user_id);

        return $bon->$generate(public_path().'/files/colloques/bon/bon_'.$this->inscription->colloque->id.'-'.$part.'.pdf');
    }

    public function factureEvent($rappel = null)
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
                'annexes'     => $this->inscription->annexe,
                'rappel'      => $rappel
            ];

            $facture  = \PDF::loadView('templates.colloque.facture', $data)->setPaper('a4');

            $generate = ($this->stream ? 'stream' : 'save');
            $folder   = ($rappel ? 'rappel': 'facture');

            return $facture->$generate(public_path().'/files/colloques/'.$folder.'/facture_'.$this->inscription->colloque->id.'-'.$this->inscription->inscrit->id.'.pdf');
        }

        return true;
    }

    public function factureGroupeEvent($groupe,$inscriptions,$price,$rappel = null)
    {
        if($price > 0)
        {
            $data = [
                'messages'     => $this->messages,
                'expediteur'   => $this->expediteur,
                'inscriptions' => $inscriptions,
                'groupe'       => $groupe,
                'date'         => $this->now,
                'signature'    => $this->signature,
                'tva'          => $this->tva,
                'annexes'      => $groupe->colloque->annexe,
                'rappel'       => $rappel
            ];

            $facture  = \PDF::loadView('templates.colloque.groupe', $data)->setPaper('a4');

            $generate = ($this->stream ? 'stream' : 'save');
            $folder   = ($rappel ? 'rappel': 'facture');

            return $facture->$generate(public_path().'/files/colloques/'.$folder.'/facture_'.$groupe->colloque_id.'-'.$groupe->id.'.pdf');

        }

        return true;
    }

    public function bvGroupeEvent($groupe,$inscriptions,$price)
    {

        if($price > 0)
        {
            $data = [
                'inscriptions' => $inscriptions,
                'groupe'       => $groupe,
            ];

            $bv = \PDF::loadView('templates.colloque.bvgroupe', $data)->setPaper('a4');

            $generate = ($this->stream ? 'stream' : 'save');

            return $bv->$generate(public_path().'/files/colloques/bv/bv_'.$groupe->colloque_id.'-'.$groupe->id.'.pdf');

        }

        return true;
    }

    public function bvEvent()
    {
        if($this->inscription->price->price > 0)
        {
            $data = ['inscription' => $this->inscription];

            $bv = \PDF::loadView('templates.colloque.bv', $data)->setPaper('a4');

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
            $this->bonEvent();
        }
        else
        {
            foreach($annexes as $annexe)
            {
                $doc = $annexe.'Event';
                $this->$doc();
            }
        }
    }

    public function factureAbo($abo, $facture, $rappel = null)
    {
        $adresse  = ($abo->tiers_id ? $abo->tiers : $abo->user);
        $msgTypes = ['warning','special','remarque','signature'];

        $data = [
            'expediteur' => $this->expediteur,
            'messages'   => $this->messages,
            'tva' => [
                'taux_reduit' => 'Taux '.$this->tva['taux_reduit'].'% inclus pour les livres'
            ],
            'compte'   => \Registry::get('shop.compte.abo'),
            'abo'      => $abo,
            'facture'  => $facture,
            'adresse'  => $adresse,
            'msgTypes' => $msgTypes,
            'date'     => $this->now,
            'rappel'   => $rappel
        ];

        $template = \PDF::loadView('templates.abonnement.facture', $data)->setPaper('a4');

        $generate   = ($this->stream ? 'stream' : 'save');
        $filename   = ($rappel ? 'rappel' : 'facture');

        $dir = public_path().'/files/abos/'.$filename.'/'.$facture->product_id;

        if (!\File::exists($dir))
        {
            \File::makeDirectory($dir);
        }

        return $template->$generate(public_path().'/files/abos/'.$filename.'/'.$facture->product_id.'/'.$filename.'_'.$facture->product->reference.'-'.$facture->abo_user_id.'_'.$facture->id.'.pdf');

    }
}