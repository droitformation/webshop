<?php

namespace App\Http\Controllers\Hub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Arret\Repo\ArretInterface;

class ContentController extends Controller
{
    protected $worker;
    protected $arret;
    protected $analyse;
    protected $categorie;
    protected $author;
    protected $page;
    protected $menu;

    public function __construct()
    {
        //abort(500);

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->worker    = \App::make('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->arret     = \App::make('App\Droit\Arret\Repo\ArretInterface');
        $this->analyse   = \App::make('App\Droit\Analyse\Repo\AnalyseInterface');
        $this->categorie = \App::make('App\Droit\Categorie\Repo\CategorieInterface');
        $this->author    = \App::make('App\Droit\Author\Repo\AuthorInterface');
        $this->page      = \App::make('App\Droit\Page\Repo\PageInterface');
        $this->menu      = \App::make('App\Droit\Menu\Repo\MenuInterface');

    }

    /*
     *  /hub/arrets?params[site_id]=1&params[years][]=2017&params[categories][]=32
     *  $params['site_id'] = 1
     *  $params['years'] = [2018,2017]
     *  $params['categories'] = [32,34]
     * */
    public function arrets(Request $request)
    {
        $arrets = $this->arret->getAllForSiteActive(
            $this->worker->excludeArrets($request->input('params')['site_id']),
            $request->input('params')['site_id'],
            $request->input('params')
        );

        return new \App\Http\Resources\ArretCollection($arrets);
    }

    public function analyses(Request $request)
    {
        $years = isset($request->input('params')['years']) ? $request->input('params')['years']: null;

        $analyses = $this->analyse->getAll(
            $request->input('params')['site_id'],
            $this->worker->excludeArrets($request->input('params')['site_id']),
            $years
        );

        return new \App\Http\Resources\AnalyseCollection($analyses);
    }

    public function categories(Request $request)
    {
        $categories = $this->categorie->getAll($request->input('params')['site_id']);

        return new \App\Http\Resources\CategorieCollection($categories);
    }

    public function years(Request $request)
    {
        $years = $this->arret->annees($request->input('params')['site_id']);

        return response()->json(['data' => $years]);
    }

    public function homepage(Request $request)
    {
        $page = $this->page->getHomepage($request->input('params')['site_id']);

        return new \App\Http\Resources\Page($page);
    }

    public function page(Request $request)
    {
        $page = $this->page->find($request->input('params')['id']);

        return new \App\Http\Resources\Page($page);
    }

    public function menu(Request $request)
    {
        $menu = $this->menu->getBySitePosition($request->input('params')['site_id'], $request->input('params')['position']);

        return $menu ? new \App\Http\Resources\Menu($menu) : null;
    }

    public function authors(Request $request)
    {
        $authors = $this->author->getBySite($request->input('params')['site_id']);

        return new \App\Http\Resources\AuthorCollection($authors, $request->input('params')['site_id']);
    }

    public function campagne(Request $request)
    {
        if(isset($request->input('params')['id']) && !empty($request->input('params')['id'])){

            if(
                (($request->input('params')['site_id'] == 4) && ($request->input('params')['id'] <= 71))
                ||
                (($request->input('params')['site_id'] == 5) && ($request->input('params')['id'] <= 263))
            ){
                $campagne = $this->worker->getCampagne($request->input('params')['id'], true);
            }
            else{
                $campagne = $this->worker->getCampagne($request->input('params')['id']);
            }
        }
        else{
            $campagne = $this->worker->lastBySite($request->input('params')['site_id']);
        }

        return new \App\Http\Resources\NewsletterCampagne($campagne);
    }

    public function archives(Request $request)
    {
        $year = isset($request->input('params')['year']) ? $request->input('params')['year'] : null;

        $newsletters = $this->worker->getArchivesBySite($request->input('params')['site_id'],$year);

        return new \App\Http\Resources\NewsletterCollection($newsletters);
    }

    public function pdf($id,$site_id)
    {
        if( (($site_id == 4) && ($id <= 71)) || (($site_id == 5) && ($id <= 263)) ){
            $campagne = $this->worker->getCampagne($id, true);
        }
        else{
            $campagne = $this->worker->getCampagne($id);
        }

        $context = stream_context_create(['ssl' => ['verify_peer' => FALSE, 'verify_peer_name' => FALSE, 'allow_self_signed'=> TRUE]]);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        $pdf = $pdf->loadView('frontend.newsletter.pdf', ['campagne' => $campagne])->setPaper('a4');

        return $pdf->stream('newsletter_'.$campagne->id.'.pdf');
    }

    /*
     * Indicate last time there was a Update
     * */

    public function maj()
    {
        $contents = getMaj('hub');

        return response()->json(['date' => $contents], 200 );
    }
}
