<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Faq\Worker\FaqWorkerInterface;
use App\Droit\Page\Repo\PageInterface;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Seminaire\Worker\SeminaireWorkerInterface;

class BailController extends Controller
{
    protected $page;
    protected $arret;
    protected $analyse;
    protected $faq;
    protected $site_id;
    protected $site;
    protected $product;
    protected $newsworker;
    protected $seminaire;

    public function __construct(ArretInterface $arret, AnalyseInterface $analyse, PageInterface $page, SiteInterface $site, FaqWorkerInterface $faq, ProductInterface $product, SeminaireWorkerInterface $seminaire)
    {
        $this->arret     = $arret;
        $this->analyse   = $analyse;
        $this->page      = $page;
        $this->faq       = $faq;
        $this->site      = $site;
        $this->product   = $product;
        $this->seminaire = $seminaire;

        $site = $this->site->findBySlug('bail');
        $this->site_id  = $site->id;

        $this->newsworker = \App::make('newsworker');
    }

    public function index()
    {
        $page = $this->page->getBySlug($this->site_id,'index');

        return view('frontend.bail.index')->with(['page' => $page]);
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

        if(!$page)
        {
            alert()->warning('Aucune page trouvé');
            return redirect('/bail');
        }

        $data['page'] = $page;
        $data['var']  = $var;

        if($slug == 'faq')
        {
            $this->faq->setSite($this->site_id)->setCategorie($var);

            $data['questions'] = $this->faq->getQuestions();;
            $data['faqcats']   = $this->faq->getCategories();
            $data['current']   = $this->faq->currentCategorie();
        }

        if($slug == 'jurisprudence')
        {
            $newsletters = $this->newsworker->siteNewsletters($this->site_id);
            $exclude     = $this->newsworker->arretsToHide($newsletters->pluck('id')->all());

            $data['arrets']   = $this->arret->getAll($this->site_id,$exclude)->take(10);
            $data['analyses'] = $this->analyse->getAll($this->site_id,$exclude)->take(10);
        }

        if($slug == 'revues')
        {
            if(isset($var)){
                $revue = $this->product->find($var);
            }
            else{
                $revue = $this->product->getByCategorie('Revue')->first();
            }

            $data['revue'] = $revue;
        }

        if($slug == 'doctrine')
        {
            $data['doctrines'] = $this->seminaire->getSubjects();
            $data['order']     = $this->seminaire->categories();
            $data['auteurs']   = $this->seminaire->authors();
            $data['annees']    = $this->seminaire->years();
        }

        if($slug == 'newsletter')
        {
            if($var) {
                $data['campagne'] = $this->newsworker->getCampagne($var);
            }
            else
            {
                $campagnes = $this->newsworker->siteCampagnes($this->site_id);

                $data['campagne'] = $campagnes->first();
            }
        }

        return view('frontend.bail.'.$page->template)->with($data);
    }

    public function unsubscribe()
    {
        return view('frontend.bail.unsubscribe');
    }
}
