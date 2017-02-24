<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Droit\Shop\Categorie\Repo\CategorieInterface;
use App\Droit\Author\Repo\AuthorInterface;
use App\Droit\Shop\Author\Repo\AuthorInterface as ShopAuthorInterface;
use App\Droit\Domain\Repo\DomainInterface;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class LabelComposer
{
    protected $categorie;
    protected $author;
    protected $shopauthors;
    protected $domain;
    protected $attribute;

    public function __construct(CategorieInterface $categorie, AttributeInterface $attribute, AuthorInterface $author, ShopAuthorInterface $shopauthors,DomainInterface $domain )
    {
        $this->categorie = $categorie;
        $this->author    = $author;
        $this->shopauthors = $shopauthors;
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
        $authors = $this->author->getAll();
        $authors = $authors->reject(function ($author) {
            return empty($author->first_name) && empty($author->last_name);
        });

        $shopauthors = $this->shopauthors->getAll();
        $shopauthors = $shopauthors->reject(function ($author) {
            return empty($author->first_name) && empty($author->last_name);
        });

        $view->with('categories', $this->categorie->getAll());
        $view->with('attributes', $this->attribute->getAll());
        $view->with('authors',    $authors);
        $view->with('shopauthors',$shopauthors);
        $view->with('domains',    $this->domain->getAll());
    }
}