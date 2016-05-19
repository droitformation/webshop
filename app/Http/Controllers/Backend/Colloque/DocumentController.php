<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Document\Repo\DocumentInterface;

class DocumentController extends Controller
{
    protected $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        return redirect()->back()->with(array('status' => 'success' , 'message' => 'Le document a été supprimé' ));
    }
}
