<?php

namespace App\Http\Controllers\Frontend;

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

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;

use App\Droit\Shop\Product\Repo\ProductInterface;

class BailController extends Controller
{
    protected $page;
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
    protected $product;

    protected $newsletter;
    protected $campagne;
    protected $worker;

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
        CalculetteWorkerInterface $calculette,
        ProductInterface $product,
        NewsletterInterface $newsletter,
        NewsletterCampagneInterface $campagne,
        CampagneInterface $worker
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
        $this->campagne      = $campagne;
        $this->worker        = $worker;
        $this->newsletter    = $newsletter;
        $this->product       = $product;

        $years      = $this->arret->annees(2);
        $categories = $this->categorie->getAll($this->site_id);
        $authors    = $this->author->getAll();

        $sites = $this->site->find(2);
        $faqcantons = [ 'be'=>'Berne','fr'=>'Fribourg', 'ge'=>'Genève', 'ju'=>'Jura', 'ne'=>'Neuchâtel', 'vs'=>'Valais', 'vd'=>'Vaud'];

        $newsletters = $this->newsletter->getAll(2);
        $revues      = $this->product->getByCategorie(25);

        view()->share('newsletters',$newsletters->first()->campagnes->pluck('sujet','id') );
        view()->share('revues',$revues->pluck('title','id') );

        view()->share('menus',$sites->menus);
        view()->share('site',$sites);
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
        $data['var']  = $var;

        if($slug == 'faq')
        {
            $faqcats   = $this->faqcat->getAll($this->site_id);
            $categorie = ($var ? $var : $faqcats->first()->id);
            $questions = $this->question->getAll($this->site_id,$categorie);

            $data['questions'] = $questions;
            $data['faqcats']   = $faqcats;
            $data['current']   = $categorie;
        }

        if($slug == 'jurisprudence')
        {
            $arrets     = $this->arret->getAll($this->site_id)->take(10);
            $analyses   = $this->analyse->getAll($this->site_id)->take(10);

            $data['arrets']   = $this->jurisprudence->preparedArrets($arrets);
            $data['analyses'] = $this->jurisprudence->preparedAnalyses($analyses);
        }

        if($slug == 'revues')
        {
            $data['revue'] = $this->product->find($var);
        }

        if($slug == 'newsletter')
        {
            if($var)
            {
                $data['campagne'] = $this->campagne->find($var);
                $data['content']  = $this->worker->prepareCampagne($var);
            }
            else
            {
                $newsletters = $this->newsletter->getAll($this->site_id)->first();
                if(!$newsletters->campagnes->isEmpty())
                {
                    $data['campagne'] = $newsletters->campagnes->first();
                    $data['content']  = $this->worker->prepareCampagne($newsletters->campagnes->first()->id);
                }
            }

            $data['categories']    = $this->worker->getCategoriesArrets();
            $data['imgcategories'] = $this->worker->getCategoriesImagesArrets();
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
