<?php

namespace App\Http\Controllers\Backend\Bail;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Faq\Repo\FaqCategorieInterface;
use App\Droit\Faq\Repo\FaqQuestionInterface;

class FaqController extends Controller
{
    protected $faqcat;
    protected $question;
    protected $site_id;

    public function __construct(FaqCategorieInterface $faqcat, FaqQuestionInterface $question)
    {
        $this->faqcat   = $faqcat;
        $this->question = $question;
        $this->site_id  = 2;
    }

    public function index()
    {
        $cats = $this->faqcat->getAll();

        return view('backend.faq.index')->with(['cats' => $cats]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categorie = $this->faqcat->create( $request->all() );

        alert()->success('Catégorie crée');

        return redirect('admin/faq');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categorie = $this->faqcat->find($id);

        return view('backend.faq.create')->with(['categorie' => $categorie]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categorie = $this->faqcat->update( $request->all() );

        alert()->success('Catégorie mise à jour');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->faqcat->delete($id);

        alert()->success('Catégorie supprimée');

        return redirect()->back();
    }

}
