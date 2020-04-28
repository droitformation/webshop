<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Content\Repo\ContentInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Page\Repo\PageInterface;

class PageContentController extends Controller
{
    protected $content;
    protected $categorie;
    protected $page;

    public function __construct(ContentInterface $content, CategorieInterface $categorie, PageInterface $page)
    {
        $this->content   = $content;
        $this->categorie = $categorie;
        $this->page      = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($type,$page)
    {
        $categories = $this->categorie->getAll(2);

        return view('backend.pages.create.'.$type)->with(['page_id' => $page, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $content = $this->content->create($request->all());

        event(new \App\Events\ContentUpdated('content'));

        flash('Le bloc a été ajouté')->success();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $content    = $this->content->find($id);
        $categories = $this->categorie->getAll(2);

        echo view('backend.pages.partials.edit')->with(['content' => $content, 'categories' => $categories]);
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

        event(new \App\Events\ContentUpdated('content'));

        flash('Le bloc a été mis à jour')->success();

        return redirect()->back();
    }

    public function sorting(Request $request)
    {
        $data = $request->all();

        $content = $this->content->updateSorting($data['page_rang']);

        event(new \App\Events\ContentUpdated('content'));

        echo 'ok';die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $this->content->delete($id);

        event(new \App\Events\ContentUpdated('content'));

        flash('Le bloc a été supprimé')->success();

        return redirect()->back();
    }

}
