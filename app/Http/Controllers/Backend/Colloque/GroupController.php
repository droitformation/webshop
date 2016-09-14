<?php
namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Option\Repo\GroupOptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class GroupController extends Controller {
    
    protected $group;
    protected $colloque;
    protected $helper;

    public function __construct(GroupOptionInterface $group, ColloqueInterface $colloque)
    {
        $this->group    = $group;
        $this->colloque = $colloque;

        $this->helper  = new \App\Droit\Helper\Helper();
    }

    public function store(Request $request)
    {
        parse_str($request->input('data'), $data);

        $this->group->create([
            'text'        => $data['text'],
            'colloque_id' => $data['colloque_id'],
            'option_id'   => $data['option_id']
        ]);

        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $group       = $this->group->find($request->input('id'));
        $colloque_id = $group->colloque_id;

        $this->group->delete($group->id);

        // Has to be called after the delete so we have the updates options
        $colloque = $this->colloque->find($colloque_id);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

}