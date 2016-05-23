<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Stock\Repo\StockInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Http\Requests\StockChangeRequest;

class StockController extends Controller
{
    protected $stock;
    protected $product;

    public function __construct(StockInterface $stock, ProductInterface $product)
    {
        $this->stock   = $stock;
        $this->product = $product;
    }

    public function update(StockChangeRequest $request)
    {
        $amount   = array_filter($request->input('amount'));
        $operator = key($amount);
        $amount   = $amount[$operator];

        // Change product sku
        $this->product->sku($request->input('product_id'), $amount, $operator);
        // Create a entry in stock history
        $this->stock->create(['product_id' => $request->input('product_id'), 'amount' => $amount, 'motif' => $request->input('motif'), 'operator' => $operator]);

        return redirect('admin/product/'.$request->input('product_id'))->with(['status' => 'success', 'message' => 'Le stock a été mis à jour']);
    }

    public function export($id)
    {
        $product = $this->product->find($id);
        $stocks  = $product->stocks;
        
        \Excel::create('Export historique stock', function($excel) use ($stocks) {
            $excel->sheet('Export_historique_stock', function($sheet) use ($stocks)
            {
                $sheet->loadView('backend.stocks.modals.table', ['stocks' => $stocks]);
            });
        })->export('xls');
    }
}
