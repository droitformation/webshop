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
    }

    public function index($site_id)
    {
        $cats = $this->faqcat->getAll($site_id);

        return view('backend.faq.index')->with(['cats' => $cats, 'current_site' => $site_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($site_id)
    {
        return view('backend.faq.create')->with(['current_site' => $site_id]);
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

        flash('Catégorie crée')->success();

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

        return view('backend.faq.show')->with(['categorie' => $categorie, 'current_site' => $categorie->site_id]);
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

        flash('Catégorie  mise à jour')->success();

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

        flash('Catégorie supprimée')->success();

        return redirect()->back();
    }

}
