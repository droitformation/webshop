<?php

namespace App\Droit\User\Worker;

use App\Droit\User\Worker\AccountWorkerInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

class AccountWorker implements AccountWorkerInterface
{
    protected $repo_adresse;
    protected $repo_user;

    public $adresse = null;
    public $user = null;
    public $data = [];

    public function __construct(AdresseInterface $repo_adresse, UserInterface $repo_user)
    {
        $this->repo_adresse = $repo_adresse;
        $this->repo_user    = $repo_user;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function createAccount($data)
    {
        $this->prepareData($data)->validate()->makeUser();

        $data = array_only($this->data,['first_name','last_name','email','company','adresse','npa','ville','cp','complement','canton_id','pays_id','civilite_id']);

        $adresse = $this->adresse ? $this->adresse : $this->repo_adresse->create($data);

        // update adresse with user_id and livraison
        $this->repo_adresse->update(['id' => $adresse->id, 'user_id' => $this->user->id, 'livraison' => 1, 'email' => $this->data['email']]);

        return $this->user->load('adresses');
    }
    
    public function makeUser()
    {
        // Create user account
        $this->user = $this->repo_user->create(array_only($this->data,['email','password','username','first_name','last_name','company']));

        return $this;
    }

    public function prepareData($data)
    {
        $this->data = $this->adresse ? array_only($this->adresse->toArray(), ['first_name','last_name','company','email','adresse','npa','ville']) + $data : $data;

        $this->uniqueEmail();

        return $this;
    }

    public function uniqueEmail()
    {

        // if there is no email or the email exist for a user already make a substitude one
        $duplicate = isset($this->data['email']) ? $this->repo_user->findByEmail($this->data['email']) : null;

        if(empty($this->data['email']) || $duplicate){

            $email = substr(md5(openssl_random_pseudo_bytes(32)),-11).'@publications-droit.ch';

            $this->data['email'] = $email;

            if($this->adresse){
                $this->repo_adresse->update(['id' => $this->adresse->id, 'email' => $email]);
            }
        }

        if(!empty($this->data['email']) && $duplicate){
            $this->data['username'] = $this->data['email'];
        }

        return $this;
    }

    public function validate()
    {
        $validator = \Validator::make($this->data, [
            'adresse'    => 'required',
            'npa'        => 'required',
            'ville'      => 'required',
            'first_name' => 'required_without:company',
            'last_name'  => 'required_without:company',
            'company'    => 'required_without_all:first_name,last_name',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:6',
        ]);

        if($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator->errors());
        }

        return $this;
    }

    public function restore($user_id){

        $user    = $this->repo_user->findWithTrashed($user_id);
        $user->restore();

        $adresse = $user->adresses_and_trashed->first(function ($adresse, $key) {
            return $adresse->type = 1;
        });

        if($user && $adresse->trashed()){
            $adresse->restore();
        }
    }
    
}