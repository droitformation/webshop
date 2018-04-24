<?php namespace App\Droit\Newsletter\Entities;

use App\Droit\Newsletter\Entities\Newsletter_contents;

class ContentModel
{
    protected $item;

    public function setModel($item){
        $this->item = $item;

        return $this;
    }

    public function convert()
    {
        if($this->item->type_id == 5){
            return $this->arret($this->item->arret);
        }

        if($this->item->type_id == 8){
            return $this->product($this->item->product);
        }

        if($this->item->type_id == 9){
            return $this->colloque($this->item->colloque);
        }

        if($this->item->type_id == 7){
            return $this->group($this->item);
        }

        if($this->item->type_id == 10){
            return $this->picto($this->item);
        }

        return null;
    }

    public function arret($arret)
    {
        return [
            'id'        =>  $arret->id,
            'droptitle' =>  $arret->reference,
            'title'     =>  $arret->title,
            'abstract'   => $arret->abstract,
            'content'   =>  $arret->pub_text,
            'link'      =>  secure_asset(config('newsletter.path.arret')).'/'. $arret->file,
            'message'   => 'Télécharger en pdf',
            'class'     => '',
            'images'    =>  $arret->categories->map(function ($categorie, $key) use ($arret) {
                return [
                    'link'  => url('jurisprudence#'. $arret->reference),
                    'image' => secure_asset(config('newsletter.path.categorie').$categorie->image),
                    'title' => $categorie->title,
                ];
            }),
        ];
    }

    public function colloque($colloque)
    {
        return [
            'id'        => $colloque->id,
            'droptitle' => $colloque->titre,
            'title'     => $colloque->titre,
            'abstract'  => $colloque->sujet,
            'content'   => $colloque->remarques,
            'link'      => url('pubdroit/colloque/').$colloque->id,
            'message'   => 'Informations et inscription',
            'class'     => '',
            'images'    => [[
                'link'  => url('pubdroit/colloque/').$colloque->id,
                'image' => $colloque->frontend_illustration,
                'title' => $colloque->titre,
            ]],
        ];
    }

    public function product($product)
    {
        return [
            'id'        => $product->id,
            'droptitle' => $product->title,
            'title'     => $product->title,
            'abstract'  => $product->teaser,
            'content'   => $product->description,
            'link'      => url('pubdroit/product/').$product->id,
            'message'   => 'Acheter',
            'class'     => '',
            'images'    => [[
                'link'  => url('pubdroit/product/').$product->id,
                'image' => !empty($product->image) ? secure_asset('files/products/'.$product->image) : null,
                'title' => $product->title,
            ]],
        ];
    }

    public function picto($model)
    {
        return [
            'id'        => $model->id,
            'droptitle' => null,
            'title'     => $model->titre,
            'abstract'  => '',
            'content'   => $model->contenu,
            'link'      => null,
            'path'     => secure_asset(config('newsletter.path.categorie')).'/',
            'message'   => null,
            'class'     => '',
            'categorie' => $model->categorie
        ];
    }

    public function group($model)
    {
        $arrets = isset($model->groupe) && !$model->groupe->arrets->isEmpty() ? $model->groupe->arrets : collect([]);

        return [
            'id'        => $model->id,
            'droptitle' => null,
            'title'     => $model->titre,
            'abstract'  => '',
            'content'   => $model->contenu,
            'link'      => null,
            'image'     => secure_asset(config('newsletter.path.categorie')).'/',
            'message'   => null,
            'class'     => '',
            'categorie' => $model->groupe->categorie,
            'arrets'    => $arrets->map(function ($arret, $key) {
                return $this->arret($arret);
             }),
            'choosen'   => $arrets->map(function ($arret, $key) {
                return [
                    'id' => $arret->id,
                    'reference' => $arret->reference,
                ];
            })
        ];

    }
}