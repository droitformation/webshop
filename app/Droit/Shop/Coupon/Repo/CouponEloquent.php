<?php namespace App\Droit\Shop\Coupon\Repo;

use App\Droit\Shop\Coupon\Repo\CouponInterface;
use App\Droit\Shop\Coupon\Entities\Coupon as M;

class CouponEloquent implements CouponInterface{

    protected $coupon;

    public function __construct(M $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getAll(){

        return $this->coupon->with(['orders'])->get();
    }

    public function find($id){

        return $this->coupon->with(['products'])->find($id);
    }

    public function findByTitle($title){

        $coupon = $this->coupon->with(['products'])->where('expire_at','>=',\Carbon\Carbon::now())->where('title', '=' , $title)->get();

        if(!$coupon->isEmpty())
        {
            return $coupon->first();
        }

        return false;
    }

    public function getGlobal()
    {
        $coupons =  $this->coupon->where('expire_at','>=',date('Y-m-d'))->whereNotNull('global')->get();
        return !$coupons->isEmpty() ? $coupons->first() : null;
    }

    public function getValid()
    {
        return $this->coupon->where('expire_at','>=',date('Y-m-d'))->get();
    }

    public function create(array $data){

        $coupon = $this->coupon->create(array(
            'title'      => $data['title'],
            'value'      => (isset($data['value']) ? $data['value'] : null),
            'type'       => $data['type'],
            'expire_at'  => $data['expire_at']
        ));

        if( ! $coupon )
        {
            return false;
        }

        // add products if any
        if(isset($data['product_id']) && !empty($data['product_id']))
        {
            $coupon->products()->attach($data['product_id']);
        }

        return $coupon;

    }

    public function update(array $data){

        $coupon = $this->coupon->findOrFail($data['id']);

        if( ! $coupon )
        {
            return false;
        }

        $coupon->fill($data);

        $coupon->save();

        // add products if any
        if(isset($data['product_id']) && !empty($data['product_id']))
        {
            $coupon->products()->sync($data['product_id']);
        }

        return $coupon;
    }

    public function delete($id){

        return $this->coupon->find($id)->delete();

    }

}
