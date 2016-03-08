<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Civilite\Repo\CiviliteInterface;
use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class LabelComposer
{
    protected $civilite;
    protected $categorie;
    protected $author;
    protected $domain;
    protected $attribute;

    public function __construct(CiviliteInterface $civilite, CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain )
    {
        $this->civilite  = $civilite;
        $this->categorie = $categorie;
        $this->author    = $author;
        $this->domain    = $domain;
        $this->attribute = $attribute;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('civilites',  $this->civilite->getAll());
        $view->with('categories', $this->categorie->getAll());
        $view->with('attributes', $this->attribute->getAll());
        $view->with('authors',    $this->author->getAll());
        $view->with('domains',    $this->domain->getAll());
    }
}