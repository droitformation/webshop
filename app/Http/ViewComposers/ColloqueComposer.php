<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Droit\Compte\Repo\CompteInterface;

class ColloqueComposer
{
    protected $location;
    protected $compte;
    protected $organisateur;

    public function __construct(LocationInterface $location, OrganisateurInterface $organisateur, CompteInterface $compte)
    {
        $this->location     = $location;
        $this->organisateur = $organisateur;
        $this->compte       = $compte;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $locations     = $this->location->getAll();
        $organisateurs = $this->organisateur->getAll();
        $comptes       = $this->compte->getAll();

        $view->with('locations', $locations);
        $view->with('organisateurs', $organisateurs);
        $view->with('comptes', $comptes);
    }
}