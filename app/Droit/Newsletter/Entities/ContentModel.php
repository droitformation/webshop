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
        $sort = str_replace('TF ','',$arret->reference);

        return [
            'id'        =>  $arret->id,
            'droptitle' =>  $arret->reference,
            'reference' =>  $arret->reference,
            'sort'      =>  $sort,
            'title'     =>  $arret->title,
            'abstract'  =>  $arret->abstract,
            'more'      => '',
            'content'   =>  $arret->pub_text,
            'dumois'    =>  $arret->dumois,
            'link'      =>  secure_asset(config('newsletter.path.arret')).'/'. $arret->filename,
            'message'   => 'Télécharger en pdf',
            'class'     => '',
            'images'    =>  $arret->categories->map(function ($categorie, $key) use ($arret) {
                return [
                    'link'  => url('jurisprudence#'. $arret->reference),
                    'id'    => $categorie->id,
                    'image' => secure_asset(config('newsletter.path.categorie').$categorie->image),
                    'title' => $categorie->title,
                ];
            }),
            'analyses' => $arret->analyses->map(function ($analyse, $key) {
                return [
                    'id'         => $analyse->id,
                    'path'       => secure_asset(config('newsletter.path.categorie').$analyse->site->slug.'/analyse.jpg'),
                    'date'       => utf8_encode($analyse->pub_date->formatLocalized('%d %B %Y')),
                    'auteurs'    => $analyse->authors->map(function ($author, $key) {
                        return [
                            'name' => $author->name,
                            'occupation' => $author->occupation,
                            'author_photo' => secure_asset(config('newsletter.path.author').$author->author_photo),
                        ];
                    }),
                    'abstract'   => $analyse->abstract,
                    'document'   => $analyse->document ? secure_asset('files/analyses/'.$analyse->filename) : null,
                ];
            })
        ];
    }

    public function colloque($colloque)
    {
        return [
            'id'        => $colloque->id,
            'droptitle' => $colloque->titre,
            'title'     => $colloque->titre,
            'abstract'  => $colloque->event_date,
            'more'      => $colloque->location ? '<p>'.$colloque->location->name.', '.strip_tags($colloque->location->adresse).'</p>' : '',
            'content'   => $colloque->themes,
            'link'      => url('pubdroit/colloque/').$colloque->id,
            'message'   => 'Programme détaillé',
            'class'     => '',
            'style'     => 'padding: 5px 10px; text-decoration: none; color: #fff; margin-top: 20px; display: inline-block;',
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
            'more'      => '',
            'content'   => $product->description,
            'link'      => url('pubdroit/product/').$product->id,
            'message'   => 'Acheter',
            'class'     => '',
            'style'     => 'padding: 5px 10px; text-decoration: none; color: #fff; margin-top: 20px; display: inline-block;',
            'images'    => [[
                'link'  => url('pubdroit/product/').$product->id,
                'image' => !empty($product->image) ? secure_asset('files/products/'.$product->image) : null,
                'title' => $product->title,
            ]],
        ];
    }

    public function picto($model)
    {
        $image = isset($model->categorie) ? $model->categorie->image : $model->image;

        return [
            'id'        => $model->id,
            'droptitle' => null,
            'title'     => $model->titre,
            'more'      => '',
            'abstract'  => '',
            'content'   => $model->contenu,
            'link'      => null,
            'path'     => secure_asset(config('newsletter.path.categorie')).'/',
            'message'   => null,
            'class'     => '',
            'categorie' =>  [
                'id'    => isset($model->categorie) ? $model->categorie->id : null,
                'title' => isset($model->categorie) ? $model->categorie->title : $model->titre,
                'image' => $image,
                'path'  => secure_asset(config('newsletter.path.categorie').$image),
            ]
        ];
    }

    public function categorie($model)
    {
        return [
            'id'    => $model->id,
            'title' => $model->title,
            'image' => $model->image,
            'path'  => secure_asset(config('newsletter.path.categorie').$model->image),
        ];
    }

    public function group($model)
    {
        $arrets = isset($model->groupe) && !$model->groupe->arrets->isEmpty() ? $model->groupe->arrets : collect([]);
        $title  = isset($model->groupe) && isset($model->groupe->categorie) ? $model->groupe->categorie->title : $model->titre;

        return [
            'id'           => $model->id,
            'droptitle'    => null,
            'title'        => $title,
            'abstract'     => '',
            'content'      => $model->contenu,
            'categorie_id' => $model->categorie_id,
            'link'         => null,
            'image'        => secure_asset(config('newsletter.path.categorie')).'/',
            'message'      => null,
            'class'        => '',
            'categorie'    =>  [
                'id'       => $model->groupe->categorie->id,
                'title'    => $model->groupe->categorie->title,
                'image'    => $model->groupe->categorie->image,
                'path'     => secure_asset(config('newsletter.path.categorie').$model->groupe->categorie->image),
            ],
            'listearrets'    => $model->groupe->categorie->arrets->map(function ($arret, $key) use ($arrets) {
                if(!$arrets->contains($arret->id)){
                    return [
                        'id' => $arret->id,
                        'reference' => $arret->reference,
                    ];
                }

                return null;
             })->reject(function ($value, $key) {
                return empty($value);
            })->sortBy('reference')->values(),
            'choosen'   => $arrets->map(function ($arret, $key) {
                return [
                    'id' => $arret->id,
                    'reference' => $arret->reference,
                ];
            })->values()
        ];

    }
}