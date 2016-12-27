<?php
namespace App\Http\Controllers\Api;

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
        $data = $request->input('occurrence');

        $occurrence = $this->occurrence->create($data);
        $colloque   = $this->colloque->find($data['colloque_id']);

        return response()->json(['occurrences' => $colloque->occurrence_display]);
    }

    /**
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->input('occurrence');
        
        $occurrence = $this->occurrence->update($data);
        $colloque   = $this->colloque->find($data['colloque_id']);

        return response()->json(['occurrences' => $colloque->occurrence_display]);
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $occurrence = $this->occurrence->find($id);
        $this->occurrence->delete($id);
        $colloque   = $this->colloque->find($occurrence->colloque_id);

        return response()->json(['occurrences' => $colloque->occurrence_display]);
    }
}