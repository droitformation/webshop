<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Author\Repo\AuthorInterface;

class BailComposer
{
    protected $site;
    protected $categorie;
    protected $arret;
    protected $author;
    protected $newsworker;

    public function __construct(SiteInterface $site, CategorieInterface $categorie, ArretInterface $arret, AuthorInterface $author)
    {
        $this->site      = $site;
        $this->categorie = $categorie;
        $this->arret     = $arret;
        $this->author    = $author;

        $this->newsworker  = \App::make('newsworker');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $site       = $this->site->findBySlug('bail');
        $categories = $this->categorie->getAll($site->id);

        $years      = $this->arret->annees($site->id);

        $newsletters = $this->newsworker->siteNewsletters($site->id);
        $campagnes   = $this->newsworker->siteCampagnes($site->id);

        $view->with('menus', $site->menus);
        $view->with('site',  $site);
        $view->with('authors', $this->author->getAll());
        $view->with('categories',  $categories);
        $view->with('years',  $years);

        $view->with('campagnes',$campagnes);
        $view->with('newsletters',$newsletters);

        $view->with('faqcantons',['be'=>'Berne','fr'=>'Fribourg','ge'=>'Genève','ju'=>'Jura','ne'=>'Neuchâtel','vs'=>'Valais','vd'=>'Vaud']);
    }
}