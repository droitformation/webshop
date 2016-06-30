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
    protected $newsworker;

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
        ProductInterface $product
    )
    {
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
        $this->product       = $product;

        $site = $this->site->findBySlug('bail');
        $this->site_id  = $site->id;

        $this->newsworker = \App::make('newsworker');

        $revues = $this->product->getByCategorie(25);

        view()->share('revues',$revues->pluck('title','id') );

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
     * @param  int  $var
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
            $newsletters = $this->newsworker->siteNewsletters($this->site_id);
            $exclude     = $this->newsworker->arretsToHide($newsletters->lists('id')->all());

            $data['arrets']   = $this->arret->getAll($this->site_id,$exclude)->take(10);
            $data['analyses'] = $this->analyse->getAll($this->site_id,$exclude)->take(10);
        }

        if($slug == 'revues')
        {
            $data['revue'] = $this->product->find($var);
        }

        if($slug == 'newsletter')
        {
            if($var)
            {
                $data['campagne'] = $this->newsworker->infos($var);
            }
            else
            {
                $newsletters = $this->newsworker->siteNewsletters($this->site_id);
                $campagnes   = $this->newsworker->last($newsletters->lists('id'));

                $data['campagne'] = $campagnes->first();
            }
        }

        return view('frontend.bail.'.$page->template)->with($data);
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

    public function unsubscribe()
    {
        return view('frontend.bail.unsubscribe');
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
