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
        $date = Carbon::now()->formatLocalized('%d %B %Y');

        $order = $this->order->find(1);
        $order->load('products','user','shipping','payement');

        $data = [
            'facdroit' => [
                'nom'     => 'Secr&eacute;tariat  - Formation',
                'adresse' => 'Avenue du 1er-Mars 26',
                'ville'   => 'CH-2000 Neuch&acirc;tel'
            ],
            'order' => $order
        ];

        return \PDF::loadView('shop.templates.facture', $data)->setPaper('a4')->stream('download.pdf');
    }


}