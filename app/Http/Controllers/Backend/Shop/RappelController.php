<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Rappel\Repo\RappelInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;

use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Jobs\NotifyJobFinished;
use App\Jobs\MakeRappelOrder;

class RappelController extends Controller
{
    protected $order;
    protected $rappel;
    protected $generator;
    
    public function __construct(OrderInterface $order, RappelInterface $rappel, PdfGeneratorInterface $generator)
    {
        $this->order  = $order;
        $this->rappel = $rappel;
        $this->generator = $generator;
    }

    /**
     * Rappels list
     * By colloque: colloque_id, type (simple or multiple), paginate
     * @param  $id
     * @return Response
     */
    public function index(Request $request)
    {
        $data = session()->has('period') ? session('period') : $request->all();

        $period['start'] = (!isset($data['start']) ? \Carbon\Carbon::now()->startOfMonth() : \Carbon\Carbon::parse($data['start']) );
        $period['end']   = (!isset($data['end'])   ? \Carbon\Carbon::now()->endOfMonth()   : \Carbon\Carbon::parse($data['end']) );

        $orders = $this->order->getRappels($period);
        
        return view('backend.orders.rappels.index')->with(['orders' => $orders, 'start' => $period['start'], 'end' => $period['end']] + $request->all());
    }

    public function make(Request $request)
    {
        $orders = $this->order->getRappels($request->all());

        if(!$orders->isEmpty())
        {
            // Make sur we have created all the rappels in pdf
            $job = (new MakeRappelOrder($orders->pluck('id')->all()));
            $this->dispatch($job);

            $job = (new NotifyJobFinished('Les rappels pour les commandes ont été crées.'));
            $this->dispatch($job);
        }

        alert()->success('La création des rappels est en cours.<br/>Un email vous sera envoyé dès que la génération sera terminée.');

        session(['period' => $request->all()]);

        return redirect()->back();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $this->rappel->delete($id);

        $order = $this->order->find($request->input('item'));
        
        return ['rappels' => $order->rappel_list];
    }

    public function generate(Request $request)
    {
        $rappel = $this->rappel->create(['order_id' => $request->input('id')]);
        $order  = $this->order->find($request->input('id'));

        $this->generator->setMsg(['warning' => config('generate.rappel.normal')]);
        $this->generator->factureOrder($order, $rappel);

        return ['rappels' => $order->rappel_list];
    }
}
