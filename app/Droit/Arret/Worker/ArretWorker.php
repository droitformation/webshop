<?php namespace App\Droit\Arret\Worker;

class ArretWorker{

    public function getAnalyseForArret($arret){

        if(!$arret->arrets_analyses->isEmpty()){

            $arret->arrets_analyses->each(function ($item, $key) {
                $item->load('analyse_authors');
            });

            return $arret->arrets_analyses;
        }

        return [];
    }

}