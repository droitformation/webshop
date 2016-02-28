<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Droit\Site\Repo\SiteInterface;


class PubdroitComposer
{
    protected $site;

    public function __construct(SiteInterface $site )
    {
        $this->site = $site;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $sites = $this->site->find(1);

        // Get first newsletter
        $newsletter = $sites->newsletter->first()->id;

        $view->with('menus', $sites->menus);
        $view->with('site',  $sites);
        $view->with('newsletter_id', $newsletter);

    }
}