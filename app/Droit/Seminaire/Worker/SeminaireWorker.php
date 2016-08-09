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

        $categories = $subjects->map(function ($subject, $key) {
            return !$subject->categories->isEmpty() ? $subject->categories->pluck('title') : false;
        })->flatten()->unique()->filter(function ($value, $key) {
            return !empty($value);
        })->map(function($subject,$key){
            return mb_strtolower($subject);
        })->sort();

        return $categories;
    }
}