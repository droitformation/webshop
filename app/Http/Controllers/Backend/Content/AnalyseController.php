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

    public function index()
    {
        $analyses   = $this->analyse->getAll();
        $categories = $this->categorie->getAll();

        return view('backend.analyses.index')->with(['analyses' => $analyses , 'categories' => $categories]);
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

        return view('backend.analyses.show')->with(['isNewsletter' => true, 'analyse' => $analyse, 'arrets' => $arrets, 'categories' => $categories, 'auteurs' => $auteurs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $arrets     = $this->arret->getAll();
        $categories = $this->categorie->getAll();
        $auteurs    = $this->author->getAll();

        return view('backend.analyses.create')->with(['isNewsletter' => true, 'arrets' => $arrets, 'categories' => $categories, 'auteurs' => $auteurs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $data  = $request->except('file');
        $_file = $request->file('file',null);

        // Files upload
        if( $_file )
        {
            $file = $this->upload->upload( $request->file('file') , 'files/analyses' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        $data['arrets']     = $this->helper->prepareCategories($request->input('arrets'));

        // Create analyse
        $analyse = $this->analyse->create( $data );

        return redirect('admin/analyse/'.$analyse->id)->with(['status' => 'success' , 'message' => 'Analyse crée']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        // Files upload
        if( $_file )
        {
            $file = $this->upload->upload( $request->file('file') , 'files/analyses' );
            $data['file'] = $file['name'];
        }

        $data['categories'] = $this->helper->prepareCategories($request->input('categories'));
        $data['arrets']     = $this->helper->prepareCategories($request->input('arrets'));

        // Create analyse
        $analyse = $this->analyse->update( $data );

        return redirect('admin/analyse/'.$analyse->id)->with(['status' => 'success' , 'message' => 'Analyse mise à jour']);

    }

    /**
     * Remove the specified resource from storage.
     * DELETE /adminconotroller/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->analyse->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Analyse supprimée' ));
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