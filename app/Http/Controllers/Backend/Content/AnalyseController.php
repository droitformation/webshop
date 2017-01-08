<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Author\Repo\AuthorInterface;

class AnalyseController extends Controller {

    protected $analyse;
    protected $author;
    protected $arret;
    protected $categorie;
    protected $upload;
    protected $custom;

    public function __construct(AuthorInterface $author, AnalyseInterface $analyse, ArretInterface $arret, CategorieInterface $categorie , UploadInterface $upload )
    {
        $this->author    = $author;
        $this->analyse   = $analyse;
        $this->arret     = $arret;
        $this->categorie = $categorie;
        $this->upload    = $upload;
        $this->helper    = new \App\Droit\Helper\Helper();

        setlocale(LC_ALL, 'fr_FR');

    }

	/**
	 * Display a listing of the resource.
	 * GET /analyse
	 *
	 * @return Response
	 */

    public function index($site)
    {
        $analyses   = $this->analyse->getAll($site);
        $categories = $this->categorie->getAll($site);

        return view('backend.analyses.index')->with(['analyses' => $analyses , 'categories' => $categories, 'current_site' => $site]);
    }

    /**
     * Return one analyse by id
     *
     * @return json
     */
    public function show($id)
    {
        $analyse    = $this->analyse->find($id);
        $arrets     = $this->arret->getAll();
        $categories = $this->categorie->getAll();
        $auteurs    = $this->author->getAll();

        return view('backend.analyses.show')->with([
            'isNewsletter' => true, 'analyse' => $analyse, 'arrets' => $arrets, 'categories' => $categories, 'auteurs' => $auteurs, 'current_site' => $analyse->site_id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site)
    {
        $arrets     = $this->arret->getAll($site);
        $categories = $this->categorie->getAll($site);
        $auteurs    = $this->author->getAll();

        return view('backend.analyses.create')->with(['isNewsletter' => true, 'arrets' => $arrets, 'categories' => $categories, 'auteurs' => $auteurs, 'current_site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data  = $request->except('file');

        // Files upload
        if( $request->file('file',null) ) {
            $file = $this->upload->upload( $request->file('file') , 'files/analyses' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        $data['arrets']     = $this->helper->prepareCategories($request->input('arrets'));

        $analyse = $this->analyse->create( $data );

        alert()->success('Analyse crée');

        return redirect('admin/analyse/'.$analyse->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $data  = $request->except('file');

        // Files upload
        if( $request->file('file',null) ) {
            $file = $this->upload->upload( $request->file('file') , 'files/analyses' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        $data['arrets']     = $this->helper->prepareCategories($request->input('arrets'));
        $data['author_id']  = $this->helper->prepareCategories($request->input('author_id'));

        $analyse = $this->analyse->update($data);

        alert()->success('Analyse mise à jour');

        return redirect('admin/analyse/'.$analyse->id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /admin/analyse/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->analyse->delete($id);

        alert()->success('Analyse supprimée');

        return redirect()->back();
    }

    /**
     * Return one analyse by id
     *
     * @return json
     */
    public function simple($id)
    {
        return $this->analyse->find($id);
    }
}