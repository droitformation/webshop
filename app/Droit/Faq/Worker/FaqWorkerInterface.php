<?php

namespace App\Droit\Faq\Worker;

interface FaqWorkerInterface{

    public function setSite($id);
    public function setCategorie($id = null);
    public function currentCategorie();
    public function getQuestions();
    public function getCategories();

}