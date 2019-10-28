<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Document\Repo\DocumentInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class DocumentController extends Controller
{
    protected $document;
    protected $colloque;

    public function __construct(DocumentInterface $document,ColloqueInterface $colloque)
    {
        $this->document = $document;
        $this->colloque = $colloque;
    }

    public function show($colloque_id,$doc)
    {
        $colloque = $this->colloque->find($colloque_id);
        $user     = \Auth::user();

        if($colloque->prices->isEmpty() || !isset($colloque->location)){
            throw new \App\Exceptions\FactureColloqueTestException('Il n\'existe pas de prix ou de lieu pour ce colloque');
        }

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->make([
            'colloque_id' => $colloque->id,
            'user_id'     => $user->id,
            'price_id'    => $colloque->prices->first()->id
        ]);

        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $generator->stream = true;
        
        return $generator->make($doc, $inscription);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->document->delete($id);

        flash('Le document a été supprimé')->success();

        return redirect()->back();
    }
}
