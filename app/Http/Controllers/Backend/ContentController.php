<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Content\Repo\ContentInterface;

class ContentController extends Controller
{
    protected $content;

    public function __construct(ContentInterface $content)
    {
        $this->content = $content;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($type,$page)
    {
        return view('backend.pages.partials.'.$type)->with(['page_id' => $page]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.contents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $content = $this->content->create($request->all());

        return redirect('admin/content/'.$content->id)->with(array('status' => 'success' , 'message' => 'Le content a été crée' ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $content  = $this->content->find($id);

        return view('backend.contents.show')->with(array( 'content' => $content ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $content = $this->content->update($request->all());

        return redirect('admin/content/'.$content->id)->with( array('status' => 'success' , 'message' => 'Le content a été mise à jour' ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->content->delete($id);

        return redirect('admin/content')->with(array('status' => 'success' , 'message' => 'La content a été supprimé' ));
    }
}
