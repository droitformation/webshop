<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

use App\Droit\Adresse\Worker\AdresseWorkerInterface;

class DeletedAdresseController extends Controller
{
    protected $adresse;
    protected $user;
    protected $worker;

    public function __construct(AdresseInterface $adresse, UserInterface $user, AdresseWorkerInterface $worker)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->worker  = $worker;
    }

    /*
     * List all and search
     * */
    public function index(Request $request)
    {
        $type     = !empty($request->input('type')) ? $request->input('type') : 'user';
        $group    = !empty($request->input('group')) ? $request->input('group') : 'user_id';
        $operator = $request->input('operator');
        
        $terms    = $this->worker->prepareTerms($request->only('terms','columns'),$type);

        $adresses = $this->$type->getDeleted($terms, $operator);

        $adresses = $adresses->groupBy($group)->map(function ($groupe, $key) {
            return (!empty($key) && is_numeric($key)) ? $groupe->unique('user_id') : $groupe;
        });

        return view('backend.deleted.index')->with(['adresses' => $adresses, 'terms' => $terms, 'type'  => $type, 'group' => $group, 'operator' => $operator]);
    }

    /*
    * Get particular adresse
    * */
    public function compare(Request $request)
    {
        $adresses = $this->adresse->getMultiple($request->input('adresses'));

        return view('backend.deleted.compare')->with(['adresses' => $adresses]);
    }

    /*
    * transvase
    * */
    public function transvase(Request $request)
    {
        $ids = $request->input('ids');
        $transvase_id = $request->input('transvase_id',null);

        // If there is a adresse for recipient and it is a user
        if($transvase_id){
            
            $recipient = $this->adresse->find($transvase_id);
            
            // we have a user
            if($recipient && isset($recipient->user)){
                
            }
        }

    }

    /*
     * Get particular adresse
     * */
    public function show($id)
    {

    }

    /*
     * Restore particular adresse
     * */
    public function restore($id)
    {

    }

    /*
     * Delete particular adresse and assign abos and/or order to main user
     * */
    public function removeAdresse(Request $request)
    {
        $user_id = $request->input('user_id');
        $id      = $request->input('id');

        //$this->adresse->assignOrdersToUser($id, $user_id);
       // $this->adresse->delete($id);
        
        $user    = $this->user->find($user_id);
        $adresse = !$user->adresses->isEmpty() ? $user->adresses->first() : null;

        echo view('backend.deleted.partials.user-row')->with(['adresse' => $adresse, 'user' => $user]);
    }

}
