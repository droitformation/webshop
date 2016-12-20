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

        return ['options' => $colloque->option_display];
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->input('option');

        $item     = $this->option->update($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return ['options' => $colloque->option_display];
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $option = $this->option->find($id);
        
        if($option->inscriptions->count() > 0) {
            return response('ERROR', 400);
        }

        $this->option->delete($option->id);
        $colloque = $this->colloque->find($option->colloque_id);

        return ['options' => $colloque->option_display];
    }

}