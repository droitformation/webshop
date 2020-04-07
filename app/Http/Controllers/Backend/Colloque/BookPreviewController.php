<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Service\UploadInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class BookPreviewController extends Controller
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
            ->withCustomProperties(['title' => $request->input('title')])
            ->toMediaCollection('preview');

        flash('Preview ajouté')->success();

        return redirect()->back();
    }

    public function update($id,Request $request)
    {
        $colloque   = $this->colloque->find($request->input('colloque_id'));
        $mediaItems = $colloque->getMedia('preview');
        $media      = $mediaItems->find($id);

        $custom = ['title' => $request->input('title')];

        if($media){
            if($request->file('file')){
                $colloque->addMediaFromRequest('file')->withCustomProperties($custom)->toMediaCollection('preview');
            }
            else{
                $colloque->addMedia($media->getPath())->withCustomProperties($custom)->toMediaCollection('preview');
            }

            $media->delete();
        }

        flash('Preview édité')->success();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $colloque   = $this->colloque->find($request->input('id'));
        $mediaItems = $colloque->getMedia('preview');
        $media = $mediaItems->find($request->input('media_id'));

        if($media){
            $media->delete();
        }

        flash('Preview supprimé')->success();

        return redirect()->back();
    }
}
