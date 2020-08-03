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

    public function options(Request $request)
    {
        $colloques = $this->colloque->getById(collect($request->input('price.linked'))->pluck('id')->all());
        $options   = $colloques->map(function ($colloque) {
            return [
                'colloque' => ['id' => $colloque->id, 'titre' => $colloque->titre],
                'options'  => $colloque->option_display
            ];
        });

        return response()->json($options);
    }

/*    public function priceoptions($colloque)
    {
        $colloque = $this->colloque->find($colloque);

        $options   = collect([$colloque])->map(function ($colloque) {
            return [
                'colloque' => ['id' => $colloque->id, 'titre' => $colloque->titre],
                'options'  => $colloque->option_display
            ];
        });

        return response()->json($options);
    }*/

    public function pricelinkoptions($price_link_id)
    {
        $price_link = $this->pricelink->find($price_link_id);
        $options    = $price_link->colloques->map(function ($colloque) {
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

    public function colloqueoptions(Request $request)
    {
        $current   = $this->colloque->find($request->input('colloque'));
        $pricelink = explode(':',$request->input('price'))[1];
        $pricelink = $this->pricelink->find($pricelink);

        return $pricelink->colloques->filter(function ($colloque) use ($current) {
            return $colloque->id != $current->id;
        })->reduce(function ($carry, $colloque) use ($request) {
            $html = view('backend.inscriptions.partials.options')->with([
                'select'   => 'groupes',
                'colloque' => $colloque,
                'form'     => $request->input('form'),
                'index'    => $request->input('index')
            ])->render();
            return $carry .= $html;
        }, '');
    }
}