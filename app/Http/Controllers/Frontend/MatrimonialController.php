<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Author\Repo\AuthorInterface;

use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Arret\Worker\JurisprudenceWorker;

class MatrimonialController extends Controller
{
    protected $arret;
    protected $categorie;
    protected $analyse;
    protected $author;
    protected $jurisprudence;
    protected $site_id;
    protected $site;

    public function __construct(
        ArretInterface $arret,
        CategorieInterface $categorie,
        AnalyseInterface $analyse,
        AuthorInterface $author,
        JurisprudenceWorker $jurisprudence,
        PageInterface $page,
        SiteInterface $site
    )
    {
        $this->site_id  = 3;

        $this->arret         = $arret;
        $this->categorie     = $categorie;
        $this->analyse       = $analyse;
        $this->author        = $author;
        $this->page          = $page;
        $this->jurisprudence = $jurisprudence;
        $this->site          = $site;

        $years      = $this->arret->annees(3);
        $categories = $this->categorie->getAll($this->site_id);
        $authors    = $this->author->getAll();

        $sites = $this->site->find(3);

        $newsworker  = \App::make('newsworker');
        $newsletters = $newsworker->siteNewsletter(2);

        view()->share('newsletters',$newsletters);
        view()->share('menus',$sites->menus);
        view()->share('site',$sites);
        view()->share('years',$years);
        view()->share('categories',$categories);
        view()->share('authors',$authors);

        setlocale(LC_ALL, 'fr_FR');
    }

    public function index()
    {
        $page = $this->page->getBySlug(3,'home');

        return view('frontend.matrimonial.index')->with([ 'page' => $page ]);
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

        if($slug == 'jurisprudence')
        {
            $data['arrets']   = $this->arret->getAll($this->site_id)->take(10);
            $data['analyses'] = $this->analyse->getAll($this->site_id)->take(10);

            //$data['arrets']   = $this->jurisprudence->preparedArrets($arrets);
            //$data['analyses'] = $this->jurisprudence->preparedAnalyses($analyses);
        }

        if($slug == 'newsletter')
        {

        }

        return view('frontend.matrimonial.'.$page->template)->with($data);
    }

    public function jurisprudence()
    {
        $arrets     = $this->arret->getAll($this->site_id)->take(10);
        $analyses   = $this->analyse->getAll($this->site_id)->take(10);

        $arrets     = $this->jurisprudence->preparedArrets($arrets);
        $analyses   = $this->jurisprudence->preparedAnalyses($analyses);

        return view('frontend.matrimonial.jurisprudence')->with(['arrets' => $arrets , 'analyses' => $analyses]);
    }

    public function newsletters($id = null)
    {
        if($id)
        {
            $campagne = $this->campagne->find($id);
            $content  = $this->worker->prepareCampagne($id);
        }
        else
        {

        }

        $categories    = $this->worker->getCategoriesArrets();
        $imgcategories = $this->worker->getCategoriesImagesArrets();

        return view('frontend.matrimonial.newsletter')->with(['arrets' => $arrets , 'analyses' => $analyses]);
    }
}
