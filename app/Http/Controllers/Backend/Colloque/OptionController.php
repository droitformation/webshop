<?php
namespace App\Http\Controllers\Backend\Colloque;

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
        parse_str($request->input('data'), $data);

        // Create option
        $option = $this->option->create($data);

        // is it's a multichoice
        if(isset($data['group']))
        {
            $choices = $this->helper->contentExplode($data['group']);

            if(!empty($choices))
            {
                foreach($choices as $choice)
                {
                    $this->group->create([
                        'text'        => $choice,
                        'colloque_id' => $data['colloque_id'],
                        'option_id'   => $option->id
                    ]);
                }
            }
        }

        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $model = $request->input('model');

        $item  = $this->option->update([ 'id' => $request->input('pk'), $request->input('name') => $request->input('value')]);

        if($item)
        {
            return response('OK', 200);
        }

        return response('OK', 200)->with(['status' => 'error','msg' => 'problème']);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $option      = $this->option->find($id);
        $colloque_id = $option->colloque_id;

        $this->option->delete($option->id);

        // Has to be called after the delete so we have the updates options
        $colloque    = $this->colloque->find($colloque_id);

        return view('backend.colloques.partials.options')->with(['colloque' => $colloque]);
    }

}