<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

use App\Droit\Adresse\Worker\AdresseWorker;

class DeletedAdresseController extends Controller
{
    protected $adresse;
    protected $user;
    protected $worker;

    public function __construct(AdresseInterface $adresse, UserInterface $user, AdresseWorker $worker)
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
        $type     = $request->input('type','user');
        $group    = $request->input('group','');
        $operator = $request->input('operator','and');
        
        $terms    = $this->worker->prepareTerms($request->only('terms','columns'),$type);

        $adresses = $this->$type->getDeleted($terms, $operator);

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

}
