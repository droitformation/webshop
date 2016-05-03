<?php namespace App\Droit\Member\Repo;

use App\Droit\Member\Repo\MemberInterface;
use App\Droit\Member\Entities\Member as M;

class MemberEloquent implements MemberInterface{

    protected $member;

    public function __construct(M $member)
    {
        $this->member = $member;
    }

    public function getAll(){

        return $this->member->orderBy('title','ASC')->get();
    }

    public function find($id){

        return $this->member->find($id);
    }

    public function search($term, $like = null)
    {
        if($like)
        {
            return $this->member->where('title','LIKE', '%'.$term.'%')->get();
        }

        return $this->member->where('title','=', $term)->get()->first();
    }

    public function create(array $data){

        $member = $this->member->create(array(
            'title' => $data['title']
        ));

        if( ! $member )
        {
            return false;
        }

        return $member;

    }

    public function update(array $data){

        $member = $this->member->findOrFail($data['id']);

        if( ! $member )
        {
            return false;
        }

        $member->fill($data);

        $member->save();

        return $member;
    }

    public function delete($id){

        $member = $this->member->find($id);

        return $member->delete();

    }
}
