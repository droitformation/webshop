<?php

namespace App\Droit\Faq\Worker;

use App\Droit\Faq\Repo\FaqQuestionInterface;
use App\Droit\Faq\Repo\FaqCategorieInterface;

class FaqWorker implements FaqWorkerInterface{

    protected $question;
    protected $faqcat;
    protected $site_id;
    protected $categorie_id;
    
    public function __construct(FaqQuestionInterface $question, FaqCategorieInterface $faqcat)
    {
        $this->question = $question;
        $this->faqcat   = $faqcat;
    }

    public function setSite($id)
    {
        $this->site_id = $id;

        return $this;
    }

    public function setCategorie($id = null)
    {
        if($id)
        {
            $this->categorie_id = $id;
        }
        
        return $this;
    }

    public function currentCategorie()
    {
        if(!$this->categorie_id)
        {
            $categories = $this->getCategories();
            
            return $categories->first()->id;
        }

        return $this->categorie_id;
    }

    public function getQuestions()
    {
        return $this->question->getAll($this->site_id,$this->currentCategorie());
    }

    public function getCategories()
    {
        return $this->faqcat->getAll($this->site_id);
    }
    
}