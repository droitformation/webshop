<?php namespace App\Droit\Shop\Order\Repo;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Order\Entities\Order as M;

class OrderEloquent implements OrderInterface{

    protected $order;

    public function __construct(M $order)
    {
        $this->order = $order;
    }

    public function getPeriod($start, $end, $status = null, $onlyfree = null)
    {
        return $this->order->with(['products','user' ,'coupon','shipping'])
            ->whereBetween('created_at', [$start, $end])
            ->status($status)
            ->isfree($onlyfree)
            ->get();
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

        return $this->order->with(['products','user','coupon','shipping'])->find($id);
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

        $order = $this->order->create(array(
            'user_id'     => (isset($data['user_id']) ? $data['user_id'] : null),
            'adresse_id'  => (isset($data['adresse_id']) ? $data['adresse_id'] : null),
            'coupon_id'   => (isset($data['coupon_id']) ? $data['coupon_id'] : null),
            'shipping_id' => $data['shipping_id'],
            'payement_id' => $data['payement_id'],
            'amount'      => $data['amount'],
            'order_no'    => $data['order_no'],
            'comment'     => (isset($data['comment']) ? $data['comment'] : null),
        ));

        if( ! $order )
        {
            return false;
        }

        // All products for order isFree
        if(!empty($data['products']))
        {
            if(isset($data['admin']))
            {
                foreach($data['products'] as $product)
                {
                    $order->products()->attach($product);
                }
            }
            else
            {
                $order->products()->attach($data['products']);
            }
        }

        return $order;
    }

    public function update(array $data){

        $order = $this->order->findOrFail($data['id']);

        if( ! $order )
        {
            return false;
        }

        $order->fill($data);

        $order->save();

        return $order;
    }

    public function delete($id){

        $order = $this->order->find($id);

        return $order->delete();
    }

}
