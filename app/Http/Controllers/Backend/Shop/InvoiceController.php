<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

class InvoiceController extends Controller {
    
    protected $order;
    protected $pdfgenerator;
    protected $export;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(OrderInterface $order, PdfGeneratorInterface $pdfgenerator)
	{
        $this->order         = $order;
        $this->pdfgenerator  = $pdfgenerator;

        $this->export = new \App\Droit\Generate\Excel\ExcelOrder();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    /**
     *
     * @return Response
     */
    public function show($id)
    {
        $order  = $this->order->find($id);
        
        if($order){
            
            $this->pdfgenerator->stream = true;
            
            if(!empty($order->comment))
                $this->pdfgenerator->setMsg(unserialize($order->comment));
            
            return  $this->pdfgenerator->factureOrder($order);
        }
    }
}
