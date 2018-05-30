<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\SendSlide;
use App\Droit\Service\UploadInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;

class SlideController extends Controller
{
    protected $colloque;
    protected $upload;
    protected $list;

    public function __construct(ColloqueInterface $colloque, UploadInterface $upload, NewsletterListInterface $list)
    {
        $this->colloque = $colloque;
        $this->upload   = $upload;
        $this->list     = $list;
    }

    public function confirm($colloque_id)
    {
        $colloque = $this->colloque->find($colloque_id);
        $worker = new \App\Droit\Sondage\Worker\SondageWorker();
        $list   = $worker->getList($colloque->id);
        $emails = $list->emails->pluck('email');

        return view('backend.colloques.confirmation')->with(['colloque' => $colloque, 'emails' => $emails]);
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

    public function update($id,Request $request)
    {
        $colloque   = $this->colloque->find($request->input('colloque_id'));
        $mediaItems = $colloque->getMedia('slides');
        $media      = $mediaItems->find($id);

        $custom = ['title' => $request->input('title'),'start_at' => $request->input('start_at'), 'end_at' => $request->input('end_at')];

        if($media){
            if($request->file('file')){
                $colloque->addMediaFromRequest('file')->withCustomProperties($custom)->toMediaCollection('slides');
            }
            else{
                $colloque->addMedia($media->getPath())->withCustomProperties($custom)->toMediaCollection('slides');
            }

            $media->delete();
        }

        alert()->success('Slide édité');

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
        $mediaItems = $colloque->getMedia('slides');
        $media = $mediaItems->find($request->input('media_id'));

        if($media){
            $media->delete();
        }

        alert()->success('Slide supprimé');

        return redirect()->back();
    }

    public function send(Request $request)
    {
        $colloque = $this->colloque->find($request->input('colloque_id'));

        $worker = new \App\Droit\Sondage\Worker\SondageWorker();
        $list   = $worker->getList($colloque->id);
        $emails = $list->emails->pluck('email');

        if(!empty($emails)){
            foreach ($emails as $email) {
                $this->dispatch(new SendSlide($email ,$colloque));
            }
        }

        $this->list->update(['id' => $list->id, 'send_at' => \Carbon\Carbon::now()->toDateTimeString()]);

        alert()->success('Le lien vers les slides ont été envoyés');

        return redirect('admin/colloque/'.$colloque->id);
    }
}
