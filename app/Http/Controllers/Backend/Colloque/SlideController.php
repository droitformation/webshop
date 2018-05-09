<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Service\UploadInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class SlideController extends Controller
{
    protected $colloque;
    protected $upload;


    public function __construct(ColloqueInterface $colloque, UploadInterface $upload)
    {
        $this->colloque = $colloque;
        $this->upload   = $upload;
    }

    public function store(Request $request)
    {
        $colloque = $this->colloque->find($request->input('colloque_id'));

        $colloque->addMediaFromRequest('file')
            ->withCustomProperties(['title' => $request->input('title'),'start_at' => $request->input('start_at'), 'end_at' => $request->input('end_at')])
            ->toMediaCollection('slides');

        alert()->success('Slide ajouté');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->colloque->delete($id);

        alert()->success('Colloque supprimé');

        return redirect('admin/colloque');
    }


}
