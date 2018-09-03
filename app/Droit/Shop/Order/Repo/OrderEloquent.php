<?php namespace App\Droit\Shop\Order\Repo;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Order\Entities\Order as M;
use App\Droit\Shop\Shipping\Entities\Paquet as P;

class OrderEloquent implements OrderInterface{

    protected $order;
    protected $paquet;

    public function __construct(M $order, P $paquet)
    {
        $this->order  = $order;
        $this->paquet = $paquet;
    }

    public function getLast($nbr)
    {
        return $this->order->with(['products','user','user.adresses','adresse'])->orderBy('created_at','DESC')->take($nbr)->get();
    }

    public function getTrashed($period)
    {
        return $this->order->with(['products','user'])->whereBetween('created_at', [$period['start'], $period['end']])->onlyTrashed()->orderBy('created_at','DESC')->get();
    }

    public function getPeriod($data)
    {
        $status   = isset($data['status']) ? $data['status'] : null;
        $send     = isset($data['send']) ? $data['send'] : null;
        $onlyfree = isset($data['onlyfree']) ? $data['onlyfree'] : null;
        
        return $this->order->with(['products','user' ,'coupon','shipping','user.adresses','adresse'])
            ->period($data['period'])
            ->status($status)
            ->send($send)
            ->isfree($onlyfree)
            ->get();
    }

    public function getRappels($period)
    {
        return $this->order->with(['products','user' ,'coupon','shipping','user.adresses','adresse'])
            ->period($period)
            ->status('pending')
            ->whereNull('payed_at')
            ->where('amount','>', 0)
            ->get();
    }

    public function getMultiple($orders)
    {
        return $this->order->with(['products','user' ,'coupon','shipping','user.adresses','adresse'])->whereIn('id',$orders)->get();
    }

    public function search($order_no)
    {
        return $this->order->with(['products','user' ,'coupon','shipping','user.adresses','adresse'])->search($order_no)->get();
    }

    public function lastYear()
    {
        $order = $this->order->orderBy('created_at', 'desc')->take(1)->get();

        if(!$order->isEmpty())
        {
            return $order->first()->created_at->format('Y');
        }

        return false;
    }

    public function hasPayed($user_id)
    {
        $days  = \Registry::get('inscription.days');

        $today = \Carbon\Carbon::now()->subDays($days);

        $notpayed = $this->order->whereNull('payed_at')->where('user_id','=',$user_id);

        if($days > 0)
        {
            $notpayed->where('created_at','<=',$today);
        }

        $notpayed = $notpayed->get();

        return ($notpayed->isEmpty() ? true : false );
    }

    public function maxOrder($year)
    {
        $order = $this->order->where('order_no','LIKE', $year.'-%')->orderBy('order_no', 'desc')->take(1)->get();

        if(!$order->isEmpty())
        {
            return $order->first();
        }

        return false;
    }

    public function find($id){

        return $this->order->with(['products','user','coupon','shipping','user.adresses','adresse'])->find($id);
    }

    public function newOrderNumber()
    {
        $lastid = 1;
        $year   = date("Y");
        $last   = $this->maxOrder($year);

        if($last)
        {
            list($y, $lastid) = explode('-', $last->order_no);
            $lastid = intval($lastid) + 1;
        }

        // Build order number
        $order_no  = str_pad($lastid, 8, '0', STR_PAD_LEFT);
        $order_no  = $year.'-'.$order_no;

        return $order_no;
    }

    public function create(array $data){

        // Shipping passed
        // only shipping_id => free | from admin set shipping
        // array with shippings models | calculation
        $order = $this->order->create(array(
            'user_id'     => (isset($data['user_id']) ? $data['user_id'] : null),
            'adresse_id'  => (isset($data['adresse_id']) ? $data['adresse_id'] : null),
            'coupon_id'   => (isset($data['coupon_id']) ? $data['coupon_id'] : null),
            'shipping_id' => (isset($data['shipping']['shipping_id']) ? $data['shipping']['shipping_id'] : null),
            'paquet'      => (isset($data['paquet']) ? $data['paquet'] : null),
            'payement_id' => $data['payement_id'],
            'amount'      => $data['amount'],
            'order_no'    => $data['order_no'],
            'comment'     => (isset($data['comment']) ? $data['comment'] : null),
        ));

        if( ! $order ) {
            return false;
        }

        if(isset($data['shipping']) && !isset($data['shipping']['shipping_id'])){
            $order = $this->setPaquets($order,$data['shipping']);
        }

        // All products for order isFree
        if(!empty($data['products'])) {
            foreach($data['products'] as $product) {
                $id = array_pull($product, 'id');

                $order->products()->attach([$id => $product]);
            }
        }

        return $order;
    }

    public function setPaquets($order,$paquets)
    {
        // Remove existing paquets
        $order->paquets()->delete();

        foreach($paquets as $paquet){
            $box = $this->paquet->create([
                'qty'         => $paquet['qty'],
                'shipping_id' => $paquet['shipping_id'],
                'remarque'    => isset($paquet['remarque']) ? $paquet['remarque'] : null
            ]);

            $order->paquets()->save($box);
        }

        return $order;
    }

    public function update(array $data){

        $order = $this->order->findOrFail($data['id']);

        if( ! $order ) {
            return false;
        }

        $order->fill($data);

        if(isset($data['comment'])) {
            $order->comment = serialize($data['comment']);
        }

        if(isset($data['coupon_id']) && !empty($data['coupon_id'])) {
            $order->coupon_id = $data['coupon_id'];
        }

        if(isset($data['payed_at']) && !empty($data['payed_at'])) {
            $valid = (\Carbon\Carbon::createFromFormat('Y-d-m', $data['payed_at']) !== false);

            $order->status = !$valid || null ? 'pending' : 'payed';
            $order->payed_at = $data['payed_at'];
        }
        else{
            $order->status = 'pending';
            $order->payed_at = null;
        }

        if(isset($data['created_at'])) {
            $order->created_at = \Carbon\Carbon::createFromFormat('Y-m-d', $data['created_at']);
        }

        if(isset($data['user_id']) && ($data['user_id'] != $order->user_id)) {
            $order->adresse_id = null;
            $order->user_id    = $data['user_id'];
        }

        if(isset($data['adresse_id'])) {
            $order->user_id    = null;
            $order->adresse_id = $data['adresse_id'];
        }

        // All products for order isFree
        if(!empty($data['products']))
        {
            $order->products()->detach();

            foreach($data['products'] as $product) {
                $id = array_pull($product, 'id');

                $order->products()->attach([$id => $product]);
            }
        }

        $order->save();

        return $order;
    }

    public function updateDate(array $data)
    {
        $order = $this->order->findOrFail($data['id']);

        if( ! $order )
        {
            return false;
        }

        $name = $data['name'];

        $order->$name = empty($data['value']) ? null : $data['value'];

        if($name == 'payed_at')
        {
            $order->status = empty($data['value']) ? 'pending' : 'payed';

            if($order->status == 'payed'){
                event(new \App\Events\OrderUpdated($order));
            }
        }

        $order->save();

        return $order;
    }

    public function delete($id){

        $order = $this->order->find($id);

        return $order->delete();
    }

    public function restore($id)
    {
        return $this->order->withTrashed()->find($id)->restore();
    }

}
