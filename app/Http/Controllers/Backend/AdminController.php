<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;

class AdminController extends Controller {

    protected $user;
    protected $adresse;

    public function __construct(UserInterface $user, AdresseInterface $adresse)
    {
        $this->user    = $user;
        $this->adresse = $adresse;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return view('backend.index');
	}

    /**
     *
     * @return Response
     */
    public function searchuser()
    {
        $users    = $this->user->getPaginate();
        $adresses = $this->adresse->getPaginate();

        return view('backend.user')->with(['users' => $users, 'adresses' => $adresses]);
    }

}
