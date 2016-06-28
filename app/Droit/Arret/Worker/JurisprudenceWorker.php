<?php namespace App\Droit\Arret\Worker;

use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Arret\Repo\ArretInterface;
use App\Droit\Analyse\Repo\AnalyseInterface;
use App\Droit\Helper\Helper;

class JurisprudenceWorker{

    protected $categories;
    protected $arret;
    protected $analyse;
    protected $custom;
    protected $newsworker;

    /* Inject dependencies */
    public function __construct(CategorieInterface $categories, ArretInterface $arret, AnalyseInterface $analyse)
    {
        $this->categories = $categories;
        $this->arret      = $arret;
        $this->analyse    = $analyse;
        $this->custom     = new \App\Droit\Helper\Helper();
        $newsworker       = \App::make('newsworker');
        
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Return collection arrets prepared for filtered
     *
     * @return collection
     */
    public function preparedAnnees()
    {
        $exclude  = $this->newsworker->arretsToHide();
        $arrets   = $this->arret->getAllActives($exclude);

        return $arrets->groupBy(function ($archive, $key) {
            return $archive->pub_date->year;
        })->keys();
    }

    public function showAnalyses(){

        $exclude  = $this->newsworker->arretsToHide();

        if(!empty($exclude))
        {
            $analyses = \DB::table('analyses_arret')->whereNotIn('arret_id', $exclude)->lists('analyse_id');
        }

        return (isset($analyses) ? $analyses : []);
    }

    /**
     * Return response arrets prepared for filtered
     *
     * @return collection
     */
    public function preparedArrets($arrets)
    {
        $prepared = $arrets->filter(function($arret)
        {
            $arret->setAttribute('humanTitle',$arret->reference.' du '.$arret->pub_date->formatLocalized('%d %B %Y'));
            $arret->setAttribute('parsedText',$arret->pub_text);

            // categories for isotope
            if(!$arret->categories->isEmpty())
            {
                foreach($arret->categories as $cat){ $cats[] = 'c'.$cat->id; }

                $cats[]  = 'y'.$arret->pub_date->year;
                $arret->setAttribute('allcats',$cats);

                return $arret;
            }
            else
            {
                $cats[]  = 'y'.$arret->pub_date->year;
                $arret->setAttribute('allcats',$cats);

                return $arret;
            }

        });

        $prepared->sortByDesc('pub_date');
        $prepared->values();

        return $prepared;
    }

    /**
     * Return collection analyses prepared for filtered
     *
     * @return collection
     */
    public function preparedAnalyses($analyses)
    {
        //$include  = $this->showAnalyses();
        //$analyses = $this->analyse->getAll($include);

        $prepared = $analyses->filter(function($analyse)
        {
            // categories for isotope
            if(!$analyse->categories->isEmpty())
            {
                foreach($analyse->categories as $cat){ $cats[] = 'c'.$cat->id; }

                $cats[]  = 'y'.$analyse->pub_date->year;
                $analyse->setAttribute('allcats',$cats);

                return $analyse;
            }
            else
            {
                $cats[]  = 'y'.$analyse->pub_date->year;
                $analyse->setAttribute('allcats',$cats);

                return $analyse;
            }

        });

        $prepared->sortByDesc('pub_date');
        $prepared->values();

        return $prepared;
    }

}