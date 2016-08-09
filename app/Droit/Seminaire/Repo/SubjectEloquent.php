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

        return $this->subject->with(['seminaires','authors','categories'])->get();
    }

    public function find($id){

        return $this->subject->with(['seminaires','authors','categories'])->find($id);
    }

    public function create(array $data){

        $subject = $this->subject->create(array(
            'title'      => $data['title'],
            'file'       => isset($data['file']) ? $data['file'] : null,
            'appendixes' => isset($data['appendixes']) ? $data['appendixes'] : null,
            'rang'       => $data['rang'],
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ));

        if( ! $subject )
        {
            return false;
        }

        // Insert related categories
        if(isset($data['categories']))
        {
            $subject->categories()->sync($data['categories']);
        }

        // Insert related authors
        if(isset($data['authors']))
        {
            $subject->authors()->sync($data['authors']);
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

        // Insert related categories
        if(isset($data['categories']))
        {
            $subject->categories()->sync($data['categories']);
        }

        // Insert related authors
        if(isset($data['authors']))
        {
            $subject->authors()->sync($data['authors']);
        }

        return $subject;
    }

    public function delete($id){

        $subject = $this->subject->find($id);

        return $subject->delete();

    }
}
