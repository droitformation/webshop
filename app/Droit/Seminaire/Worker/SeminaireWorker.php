<?php

namespace App\Droit\Seminaire\Worker;

use App\Droit\Seminaire\Repo\SeminaireInterface;
use App\Droit\Seminaire\Repo\SubjectInterface;

use App\Droit\Seminaire\Worker\SeminaireWorkerInterface;

class SeminaireWorker implements SeminaireWorkerInterface
{
    protected $seminaire;
    protected $subject;
    
    public function __construct(SeminaireInterface $seminaire, SubjectInterface $subject)
    {
        $this->seminaire = $seminaire;
        $this->subject   = $subject;
    }

    public function getSubjects()
    {
        $subjects = $this->subject->getAll();

        return $subjects->groupBy(function ($subject, $key) {
            return $subject->main_categorie;
        });
    }

    public function categories()
    {
        $subjects = $this->subject->getAll();

        return $subjects->map(function ($subject, $key) {
            return !$subject->categories->isEmpty() ? $subject->categories->pluck('title','id') : false;
        })->filter(function ($value, $key) {
            return $value;
        })->flattenWithKey()->map(function($subject,$key){
            return mb_strtolower($subject);
        })->sort();
    }


    public function authors()
    {
        $subjects = $this->subject->getAll();

        return $subjects->map(function ($subject, $key) {
            return !$subject->authors->isEmpty() ? $subject->authors->pluck('name','id') : false;
        })->filter(function ($value, $key) {
            return $value;
        })->flattenWithKey()->sort();
    }

    public function years()
    {
        $subjects = $this->subject->getAll();

        return $subjects->map(function ($subject, $key) {
            return !$subject->seminaires->isEmpty() ? $subject->seminaire->year : false;
        })->flatten()->unique()->filter(function ($value, $key) {
            return $value;
        })->sort()->reverse();
    }
}