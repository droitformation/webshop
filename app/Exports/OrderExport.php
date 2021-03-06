<?php namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromArray, WithHeadings, WithEvents, WithColumnFormatting
{
    use Exportable;

    protected $orders;
    protected $title;
    protected $columns;
    protected $total;

    protected $details;

    public function __construct($orders,$columns, $title, $details = null)
    {
        $this->orders  = $orders;
        $this->columns = $columns;
        $this->title   = $title;
        $this->details = $details;

   /*     $this->total = $sum = $this->orders->reduce(function ($carry, $item) {
            return $carry + $item->amount;
        },0);*/
    }

    public function headings(): array {
        return [$this->title];
    }

    public function array(): array
    {
        $orders  = $this->prepareOrder($this->orders);

        $columns = ['Numero','Date','Prix','Port','Total','Paye','Status'];
        $details = ['Titre','Quantité','Prix normal','Prix','Gratuit','Rabais'];

        $header = $this->details ? array_merge($columns,$details) : $columns;
        $header = $this->columns ? array_merge($header,$this->columns) : $header;

        return array_merge([[''],$header] ,$orders,[['']]);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:C1'; // Titles
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(17);
                $cellRange2 = 'A3:AA3'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setBold(true);
            },
        ];
    }

    public function prepareOrder($orders)
    {
        $converted = [];

        foreach($orders as $order) {

            $user = [];

            $montant = $order->amount > 0 ? round($order->amount/100,2) : 0;
            $montant = floatval(str_replace(",",".",$montant));

            setlocale(LC_NUMERIC, 'en_US');

            $info['Numero']  = $order->order_no;
            $info['Date']    = $order->created_at->format('d.m.Y');
            $info['Prix']    = $montant > 0 ? $montant : '0';
            $info['Port']    = $order->total_shipping;
            $info['Total']   = $order->total_with_shipping;
            $info['Paye']    = $order->payed_at ? $order->payed_at->format('d.m.Y') : '';
            $info['Status']  = $order->total_with_shipping > 0 ? $order->status_code['status']: 'Gratuit';

            if($this->columns){
                if($order->order_adresse) {
                    // Get columns requested from user adresse
                    foreach(array_keys($this->columns) as $column) {
                        $user[$column] = $order->order_adresse->$column;
                    }
                }
            }

            if($this->details) {
                // Only details of each order and group by product in order and count qty
                $grouped = $order->products->groupBy(function ($item, $key) {
                    return $item->id.$item->pivot->price.$item->pivot->rabais.$item->pivot->isFree;
                });

                foreach($grouped as $product) {
                    $original = $product->first()->price_normal ? $product->first()->price_normal : null;
                    $special  = couponCalcul($order,$product->first());

                    $rabais = couponProductOrder($order,$product->first());

                    $data['title']    = $product->first()->title;
                    $data['qty']      = $product->count();
                    $data['original'] = $original;
                    $data['prix']     = $special > 0 ? $special : '0';
                    $data['free']     = $product->first()->pivot->isFree ? 'Oui' : '';
                    $data['rabais']   = $rabais;

                    $converted[] = $info + $data + $user;
                }
            }
            else {
                $converted[] = $info + $user;
            }
        } // end foreach

        return $converted;
    }

    public function columnFormats(): array
    {
        return [
            /*  */
            'C' => NumberFormat::FORMAT_GENERAL,
            'E' => NumberFormat::FORMAT_GENERAL,
            //'I' => '0.00',
            //'J' => '0.00',

        ];
    }
}