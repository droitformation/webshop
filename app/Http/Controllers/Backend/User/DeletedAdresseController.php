<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;

class DeletedAdresseController extends Controller
{
    protected $adresse;
    protected $user;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
    }

    /*
     * List all and search
     * */
    public function index(Request $request)
    {
        $adresses = $this->adresse->getDeleted();
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
