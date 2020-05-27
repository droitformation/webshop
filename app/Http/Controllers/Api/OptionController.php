<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Option\Repo\GroupOptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\PriceLink\Repo\PriceLinkInterface;

class OptionController extends Controller {

    protected $option;
    protected $group;
    protected $colloque;
    protected $pricelink;
    protected $helper;

    public function __construct(OptionInterface $option, GroupOptionInterface $group, ColloqueInterface $colloque, PriceLinkInterface $pricelink)
    {
        $this->option    = $option;
        $this->group     = $group;
        $this->colloque  = $colloque;
        $this->pricelink = $pricelink;

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    public function index($colloque)
    {
        $colloque = $this->colloque->find($colloque);

        return response()->json($colloque->option_display);
    }

    public function priceoptions($price_link_id,$colloque_id)
    {
        $price_link = $this->pricelink->find($price_link_id);
        $colloques  = $price_link->colloques->whereNotIn('id',[$colloque_id]);
        $options    = $colloques->map(function ($colloque) {
            return [
                'colloque' => ['id' => $colloque->id, 'titre' => $colloque->titre],
                'options'  => $colloque->option_display
            ];
        });

        return response()->json($options);
    }

    public function store(Request $request)
    {
        $data = $request->input('option');

        $option   = $this->option->create($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return response()->json(['options' => $colloque->option_display]);
    }

    public function update($id,Request $request)
    {
        $data = $request->input('option');

        $data['groupe'] = isset($data['groupe']) && !empty($data['groupe']) ? collect($data['groupe'])->reject(function ($item, $key) {
            return empty($item['text']);
        })->each(function ($group, $key) {
            $this->group->update(['id' => $group['id'], 'text' => $group['text']]);
        }) : [];

        $item     = $this->option->update($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return response()->json(['options' => $colloque->option_display]);
    }

    public function destroy($id)
    {
        $option = $this->option->find($id);

        $this->option->delete($option->id);
        $colloque = $this->colloque->find($option->colloque_id);

        return response()->json(['options' => $colloque->option_display]);
    }
}