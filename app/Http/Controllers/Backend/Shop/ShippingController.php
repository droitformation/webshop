<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingRequest;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;

class ShippingController extends Controller
{
    protected $shipping;

    public function __construct(ShippingInterface $shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippings = $this->shipping->getAll();

        return view('backend.shippings.index')->with(['shippings' => $shippings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.shippings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShippingRequest $request)
    {
        $shipping  = $this->shipping->create($request->all());

        alert()->success('Le frais de port a été crée');

        return redirect('admin/shipping');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shipping = $this->shipping->find($id);

        return view('backend.shippings.show')->with(['shipping' => $shipping]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShippingRequest $request, $id)
    {
        $shipping  = $this->shipping->update($request->all());

        alert()->success('Le frais de port a été mis à jour');

        return redirect('admin/shipping');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->shipping->delete($id);

        alert()->success('Le frais de port a été supprimé');

        return redirect('admin/shipping');
    }
}
