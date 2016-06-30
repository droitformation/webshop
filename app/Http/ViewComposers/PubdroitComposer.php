<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Droit\Site\Repo\SiteInterface;


class PubdroitComposer
{
    protected $site;
    protected $newsworker;

    public function __construct(SiteInterface $site )
    {
        $this->site = $site;
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
        $site        = $this->site->findBySlug('pubdroit');
        $newsletters = $this->newsworker->siteNewsletters($site->id);
        $campagnes   = $this->newsworker->siteCampagnes($site->id);

        $view->with('menus', $site->menus);
        $view->with('site',  $site);

        $view->with('campagnes',$campagnes);
        $view->with('newsletters',$newsletters);

    }
}