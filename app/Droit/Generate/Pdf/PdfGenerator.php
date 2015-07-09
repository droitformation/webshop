<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;
use App\Droit\Shop\Order\Repo\OrderInterface;

class PdfGenerator
{
    protected $order;

    public function __construct(OrderInterface $order)
    {
        $this->order  = $order;
    }
    public function facture($id)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date  = Carbon::now()->formatLocalized('%d %B %Y');

        $order = $this->order->find(4);
        $order->load('products','user','shipping','payement');
        $order->user->load('adresses');

        $products = $order->products->groupBy('id');
        $msgTypes = ['warning','special','remarque','signature'];
/*
        $products = $order->products;
        echo '<pre>';
        print_r($products->groupBy('id')->toArray());
        echo '</pre>';exit;*/

        $data = [
            'expediteur' => [
                'nom'     => 'Secr&eacute;tariat  - Formation',
                'adresse' => 'Avenue du 1er-Mars 26',
                'ville'   => 'CH-2000 Neuch&acirc;tel'
            ],
            'messages'   => [
                'warning'   => 'Les livres ne sont ni repris, ni échangés. Aucune note de crédit ne sera délivrée.',
                'special'   => 'Rabais de 5% appliqué sur le prix éditeur',
                'remarque'  => 'Une remarque en plus pour cette facture.',
                'signature' => 'Avec nos remerciements, nous vous adressons nos salutations les meilleures',
            ],
            'versement' => [
                'nom'     => 'Université de Neuchâtel',
                'adresse' => 'Séminaire sur le droit du bail',
                'ville'   => '2000 Neuchâtel'
            ],
            'motif' => [
                'centre' => 'U. 01852',
                'texte'  => 'Vente ouvrages',
            ],
            'tva' => [
                'numero'      => 'CHE-115.251.043TVA',
                'taux_réduit' => 'Taux 2.5% inclus pour les livres',
                'taux_normal' => 'Taux 8% pour les autres produits'
            ],
            'compte' => '20-4130-2',
            'order'     => $order,
            'products'  => $products,
            'msgTypes'  => $msgTypes,
            'date'      => $date
        ];

        return \PDF::loadView('shop.templates.facture', $data)->setPaper('a4')->stream('download.pdf');
    }


}