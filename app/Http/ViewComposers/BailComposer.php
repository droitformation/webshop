<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Droit\Site\Repo\SiteInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

class BailComposer
{
    protected $site;
    protected $categorie;
    protected $arret;
    protected $author;
    protected $product;
    protected $newsworker;

    public function __construct(SiteInterface $site, CategorieInterface $categorie, ArretInterface $arret, AuthorInterface $author, ProductInterface $product)
    {
        $this->site      = $site;
        $this->categorie = $categorie;
        $this->arret     = $arret;
        $this->author    = $author;
        $this->product   = $product;

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
        setlocale(LC_ALL, 'fr_FR');
        
        $site       = $this->site->findBySlug('bail');
        $categories = $this->categorie->getAll($site->id);
        $revues     = $this->product->getByCategorie('Revue');

        $years      = $this->arret->annees($site->id);

        $newsletters = $this->newsworker->siteNewsletters($site->id);
        $campagnes   = $this->newsworker->siteCampagnes($site->id);

        if(!$site->menus->isEmpty())
        {
            foreach($site->menus as $menu)
            {
                $menu->load('pages');
                $view->with('menu_'.$menu->position, $menu);
            }
        }
        
        $view->with('site',  $site);
        $view->with('authors', $this->author->getAll());
        $view->with('categories',  $categories);
        $view->with('revues', $revues->pluck('title','id'));
        $view->with('years',  $years);

        $view->with('campagnes',$campagnes);
        $view->with('newsletters',$newsletters);

        $view->with('faqcantons',['be'=>'Berne','fr'=>'Fribourg','ge'=>'Genève','ju'=>'Jura','ne'=>'Neuchâtel','vs'=>'Valais','vd'=>'Vaud']);
    }
}