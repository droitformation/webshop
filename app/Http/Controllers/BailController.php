<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Faq\Repo\FaqQuestionInterface;
use App\Droit\Faq\Repo\FaqCategorieInterface;

use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Arret\Worker\JurisprudenceWorker;

use App\Droit\Calculette\Worker\CalculetteWorkerInterface;

class BailController extends Controller
{
    protected $arret;
    protected $categorie;
    protected $analyse;
    protected $author;
    protected $jurisprudence;
    protected $question;
    protected $faqcat;
    protected $site_id;
    protected $site;
    protected $calculette;

    public function __construct(
        ArretInterface $arret,
        CategorieInterface $categorie,
        AnalyseInterface $analyse,
        AuthorInterface $author,
        JurisprudenceWorker $jurisprudence,
        PageInterface $page,
        SiteInterface $site,
        FaqQuestionInterface $question,
        FaqCategorieInterface $faqcat,
        CalculetteWorkerInterface $calculette
    )
    {
        $this->site_id  = 2;

        $this->arret         = $arret;
        $this->categorie     = $categorie;
        $this->analyse       = $analyse;
        $this->author        = $author;
        $this->page          = $page;
        $this->jurisprudence = $jurisprudence;
        $this->question      = $question;
        $this->faqcat        = $faqcat;
        $this->site          = $site;
        $this->calculette    = $calculette;

        $years      = $this->arret->annees(2);
        $categories = $this->categorie->getAll($this->site_id);
        $authors    = $this->author->getAll();

        $menus = $this->site->find(2);
        $faqcantons = [ 'be'=>'Berne','fr'=>'Fribourg', 'ge'=>'Genève', 'ju'=>'Jura', 'ne'=>'Neuchâtel', 'vs'=>'Valais', 'vd'=>'Vaud'];

        view()->share('menus',$menus->menus);
        view()->share('years',$years);
        view()->share('categories',$categories);
        view()->share('authors',$authors);
        view()->share('faqcantons',$faqcantons);

        setlocale(LC_ALL, 'fr_FR');
    }

    public function index()
    {
        $page = $this->page->getBySlug(2,'index');

        return view('frontend.bail.index')->with([ 'page' => $page ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return Response
     */
    public function page($slug,$var = null)
    {
        $page = $this->page->getBySlug($this->site_id,$slug);

        $data['page'] = $page;

        if($slug == 'faq')
        {
            $faqcats   = $this->faqcat->getAll($this->site_id);
            $categorie = ($var ? $var : $faqcats->first()->id);
            $questions = $this->question->getAll($this->site_id,$categorie);

            $data['questions'] = $questions;
            $data['faqcats']   = $faqcats;
            $data['current']   = $categorie;
        }

        return view('frontend.bail.'.$page->template)->with($data);
    }

    public function jurisprudence()
    {
        $arrets     = $this->arret->getAll($this->site_id);
        $analyses   = $this->analyse->getAll($this->site_id);

        $arrets     = $this->jurisprudence->preparedArrets($arrets);
        $analyses   = $this->jurisprudence->preparedAnalyses($analyses);

        return view('frontend.bail.jurisprudence')->with(['arrets' => $arrets , 'analyses' => $analyses]);
    }

    public function loyer(Request $request)
    {
        $data = $request->all();

        if(!empty( $data ))
        {
            $date = Carbon::createFromFormat('d/m/Y', $request->input('date'))->toDateTimeString();

            return $this->calculette->calculer($request->input('canton'), $date, $request->input('loyer'));
        }

        return [];
    }

    /*    public function doctrine(){

            $subjects   = $this->subject->getAll();
            $categories = $this->subject->arrangeCategories($subjects);
            $seminaires = $this->seminaire->getAll();

            return view('bail.doctrine')->with( array( 'seminaires' => $seminaires , 'subjects' => $subjects  ,'categories' => $categories ));
        }


        public function search(){

            $query = Request::get('q');

            $resultats = array();

            return view('bail.search')->with( array( 'resultats' => $query ));
        }


    */


}
