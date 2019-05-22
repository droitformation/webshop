<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Option\Repo\GroupOptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class OptionController extends Controller {

    protected $option;
    protected $group;
    protected $colloque;
    protected $helper;

    public function __construct(OptionInterface $option, GroupOptionInterface $group, ColloqueInterface $colloque)
    {
        $this->option   = $option;
        $this->group    = $group;
        $this->colloque = $colloque;

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->input('option');

        $option   = $this->option->create($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return response()->json(['options' => $colloque->option_display]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->input('option');

        collect($data['groupe'])->reject(function ($item, $key) {
            return empty($item['text']);
        })->each(function ($group, $key) {
            $this->group->update(['id' => $group['id'], 'text' => $group['text']]);
        });

        $item     = $this->option->update($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return response()->json(['options' => $colloque->option_display]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $option = $this->option->find($id);

        $this->option->delete($option->id);
        $colloque = $this->colloque->find($option->colloque_id);

        return response()->json(['options' => $colloque->option_display]);
    }
}