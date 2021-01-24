<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Seminaire\Repo\SeminaireInterface;
use App\Droit\Seminaire\Repo\SubjectInterface;
use App\Droit\Service\UploadInterface;

class SubjectController extends Controller
{
    protected $seminaire;
    protected $subject;
    protected $upload;

    public function __construct(SubjectInterface $subject, SeminaireInterface $seminaire, UploadInterface $upload)
    {
        $this->seminaire = $seminaire;
        $this->subject   = $subject;
        $this->upload    = $upload;

        view()->share('current_site', 2);
    }

    public function annexe(Request $request)
    {
        if($request->input('link') && $request->input('id')){
            $subject = $this->subject->find($request->input('id'));
            $subject = $this->subject->update(['id' => $request->input('id'),'appendixes' => delete_in_array($subject->appendixes,$request->input('link'))]);
        }

        return view('backend.seminaires.partials.annexes')->with(['subject' => $subject]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /subject
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->except('file','appendixes');

        if($request->file('file')){
            $file  = $this->upload->upload( $request->file('file') , 'files/subjects');
            $data['file'] = $file['name'];
        }

        if(!empty($request->file('appendixes',[]))){
            foreach ($request->file('appendixes') as $annexe){
                $appendixe = $this->upload->upload( $annexe , 'files/subjects');
                $files[] = $appendixe['name'];
            }

            $data['appendixes'] = implode(',',$files);
        }

        $seminaire = $this->seminaire->find($request->input('seminaire_id'));
        $subject   = $this->subject->create($data);
        
        $seminaire->subjects()->save($subject);

        event(new \App\Events\ContentUpdated('hub'));

        flash('Sujet crée')->success();

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     * PUT /subject/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $data = $request->except('file','appendixes');
        $subject = $this->subject->find($request->input('id'));

        if($request->file('file')){
            $file  = $this->upload->upload( $request->file('file') , 'files/subjects');
            $data['file'] = $file['name'];
        }

        if(!empty($request->file('appendixes',[]))){
            foreach ($request->file('appendixes') as $annexe){
                $appendixe = $this->upload->upload( $annexe , 'files/subjects');
                $files[] = $appendixe['name'];
            }

            $exist      = explode(',',$subject->appendixes);
            $appendixes = array_merge($files,$exist);

            $data['appendixes'] = implode(',',$appendixes);
        }

        $subject = $this->subject->update( $data );

        event(new \App\Events\ContentUpdated('hub'));

        flash('Sujet mis à jour')->success();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /subject/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->subject->delete($id);

        flash('Sujet supprimé')->success();

        return redirect()->back();
    }

}
