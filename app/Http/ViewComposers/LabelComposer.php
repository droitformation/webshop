<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class LabelComposer
{
    protected $categorie;
    protected $author;
    protected $domain;
    protected $attribute;

    public function __construct(CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, DomainInterface $domain )
    {
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
        $view->with('categories', $this->categorie->getAll());
        $view->with('attributes', $this->attribute->getAll());
        $view->with('authors',    $this->author->getAll());
        $view->with('domains',    $this->domain->getAll());
    }
}