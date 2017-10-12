<?php namespace App\Droit\Process\Repo;

use App\Droit\Process\Repo\JobInterface;
use App\Droit\Process\Entities\Job as M;

class JobEloquent implements JobInterface{

    protected $job;

    public function __construct(M $job)
    {
        $this->job = $job;
    }

    public function getAll(){

        return $this->job->all();
    }

    public function find($id){

        return $this->job->find($id);
    }

    public function delete($id){

        $job = $this->job->find($id);

        if(!$job){

            $error = [
                'Action' => 'Suppression d\'une campagne',
                'message' => 'Problème avec la suppression du job '.$id
            ];

            \Mail::send('emails.alert', ['error' => $error], function ($m) {
                $m->from('info@publications-droit.ch', 'Administration Droit Formation');
                $m->to('cindy.leschaud@gmail.com', 'Webmaster')->subject('Alerte dans l\'administration');
            });

            echo 'Problème avec la suppression du job, avertir le webmaster!<br/><a href="'.url('/admin').'">Retour à l\'admin</a>';die();
        }

        return $job->delete($id);
    }
}
