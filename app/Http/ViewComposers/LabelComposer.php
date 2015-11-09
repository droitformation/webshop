<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Specialisation\Repo\SpecialisationInterface;
use App\Droit\Member\Repo\MemberInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

class LabelComposer
{
    protected $profession;
    protected $canton;
    protected $pays;
    protected $specialisation;
    protected $member;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(
        ProfessionInterface $profession,
        PaysInterface $pays,
        CantonInterface $canton,
        MemberInterface $member,
        SpecialisationInterface $specialisation
    )
    {
        $this->profession     = $profession;
        $this->canton         = $canton;
        $this->pays           = $pays;
        $this->member         = $member;
        $this->specialisation = $specialisation;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $professions     = $this->profession->getAll();
        $cantons         = $this->canton->getAll();
        $pays            = $this->pays->getAll();
        $members         = $this->member->getAll();
        $specialisations = $this->specialisation->getAll();

        $view->with('pays',$pays);
        $view->with('cantons',$cantons);
        $view->with('professions',$professions);
        $view->with('members',$members);
        $view->with('specialisations',$specialisations);
    }
}