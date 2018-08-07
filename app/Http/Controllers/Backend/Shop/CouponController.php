<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Droit\Shop\Coupon\Repo\CouponInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class CouponController extends Controller
{
    protected $coupon;
    protected $product;

    public function __construct(CouponInterface $coupon,ProductInterface $product)
    {
        $this->coupon  = $coupon;
        $this->product = $product;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = $this->coupon->getAll();

        return view('backend.coupons.index')->with(['coupons' => $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->product->getAll();

        return view('backend.coupons.create')->with(['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $coupon  = $this->coupon->create($request->all());

        return redirect('admin/coupon')->with(array('status' => 'success', 'message' => 'Le coupon a été crée' ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $coupon   = $this->coupon->find($id);
        $products = $this->product->getAll();

        return view('backend.coupons.show')->with(['coupon' => $coupon,'products' => $products]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, $id)
    {
        $coupon = $this->coupon->update($request->all());

        return redirect('admin/coupon')->with(['status' => 'success', 'message' => 'Le coupon a été mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->coupon->delete($id);

        return redirect('admin/coupon')->with(['status' => 'success' , 'message' => 'Le coupon a été supprimé']);
    }
}
