<?php namespace App\Droit\Seminaire\Repo;

use App\Droit\Seminaire\Repo\SubjectInterface;
use App\Droit\Seminaire\Entities\Subject as M;

class SubjectEloquent implements SubjectInterface{

    protected $subject;

    public function __construct(M $subject)
    {
        $this->subject = $subject;
    }

    public function getAll(){

        return $this->subject->with(['seminaire','authors','categories'])->get();
    }

    public function find($id){

        return $this->subject->with(['seminaire','authors','categories'])->find($id);
    }

    public function create(array $data){

        $subject = $this->subject->create(array(
            'title'      => $data['title'],
            'file'       => $data['file'],
            'appendixes' => $data['appendixes'],
            'rang'       => $data['rang'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ));

        if( ! $subject )
        {
            return false;
        }

        return $subject;

    }

    public function update(array $data){

        $subject = $this->subject->findOrFail($data['id']);

        if( ! $subject )
        {
            return false;
        }

        $subject->fill($data);

        $subject->save();

        return $subject;
    }

    public function delete($id){

        $subject = $this->subject->find($id);

        return $subject->delete();

    }
}
