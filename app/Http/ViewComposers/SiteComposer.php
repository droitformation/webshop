<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Site\Repo\SiteInterface;

class SiteComposer
{
    protected $site;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct( SiteInterface $site)
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
        $sites = $this->site->getAll();

        $view->with('sites',$sites);
    }
}