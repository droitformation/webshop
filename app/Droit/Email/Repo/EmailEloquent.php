<?php namespace App\Droit\Email\Repo;

use App\Droit\Email\Repo\EmailInterface;
use App\Droit\Email\Entities\Email as M;

class EmailEloquent implements EmailInterface{

    protected $email;

    public function __construct(M $email)
    {
        $this->email = $email;
    }

    public function getAll($period)
    {
        if(empty($period)){
            return $this->email->orderBy('id','desc')->paginate(15);
        }

        return $this->email->period($period)->orderBy('id','desc')->get();
    }

    public function search($email)
    {
        return $this->email->where('to', 'like', '%'.$email.'%')->get();
    }

    public function find($id)
    {
        return $this->email->find($id);
    }
    
    public function delete($id)
    {
        $email = $this->email->find($id);

        return $email->delete();
    }
}
