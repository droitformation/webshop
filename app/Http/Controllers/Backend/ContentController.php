<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Content\Repo\ContentInterface;
use App\Droit\Categorie\Repo\CategorieInterface;

class ContentController extends Controller
{
    protected $content;
    protected $categorie;

    public function __construct(ContentInterface $content, CategorieInterface $categorie)
    {
        $this->content   = $content;
        $this->categorie = $categorie;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($type,$page)
    {
        $categories = $this->categorie->getAll(2);

        return view('backend.pages.partials.'.$type)->with(['page_id' => $page, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->input('data');

        $content = $this->content->create($data);

        echo 'ok';
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

        return view('backend.pages.partials.'.$content->type)->with(['page_id' => $content->page_id, 'content' => $content]);
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

        echo 'ok';
    }
}
