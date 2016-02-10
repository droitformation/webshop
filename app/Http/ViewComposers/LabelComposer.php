<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;

class LabelComposer
{
    protected $categorie;
    protected $attribute;
    protected $author;
    protected $domain;

    public function __construct(CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain )
    {
        $this->categorie = $categorie;
        $this->attribute = $attribute;
        $this->author    = $author;
        $this->domain    = $domain;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('categories', $this->categorie->getAll());
        $view->with('attributes', $this->attribute->getAll());
        $view->with('authors',    $this->author->getAll());
        $view->with('domains',    $this->domain->getAll());
    }
}