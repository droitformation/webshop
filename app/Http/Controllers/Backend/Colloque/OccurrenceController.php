<?php
namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Occurrence\Repo\OccurrenceInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class OccurrenceController extends Controller {

    protected $occurrence;
    protected $colloque;

    public function __construct(OccurrenceInterface $occurrence, ColloqueInterface $colloque)
    {
        $this->occurrence  = $occurrence;
        $this->colloque    = $colloque;
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function store(Request $request)
    {
        parse_str($request->input('data'), $data);

        $new      = $this->occurrence->create($data);
        $colloque = $this->colloque->find($data['colloque_id']);

        return view('backend.colloques.partials.occurrences')->with(['colloque' => $colloque]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $item  = $this->occurrence->update(['id' => $request->input('pk'), $request->input('name') => $request->input('value')]);

        if($item)
        {
            return response('OK', 200);exit;;
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
        // find model to delete
        $item     = $this->occurrence->find($id);
        // Find corresponding colloque
        $colloque = $this->colloque->find($item->colloque_id);

        // delete item
        $this->occurrence->delete($item->id);

        return view('backend.colloques.partials.occurrences')->with(['colloque' => $colloque]);
    }
}