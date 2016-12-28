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
        $centres  = $this->organisateur->centres();

        foreach($locations as $index => $location)
        {
            $locations_json[$index]['value'] = $location->id;
            $locations_json[$index]['text']  = htmlspecialchars($location->name);
        }

        $adresses = $organisateurs->reject(function ($item) { return $item->adresse == ''; });

        $view->with('adresses', $adresses);
        $view->with('centres', $centres);
        $view->with('locations', $locations);
        $view->with('organisateurs', $organisateurs);
        $view->with('comptes', $comptes);

        $view->with('locations_json', $locations_json);
    }
}