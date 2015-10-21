<?php namespace App\Droit\Arret\Worker;

class ArretWorker{

    public function getAnalyseForArret($arret){

        if(!$arret->arrets_analyses->isEmpty()){

            $arret->arrets_analyses->load('analyse_authors');
            return $arret->arrets_analyses;
        }

        return [];
    }

}