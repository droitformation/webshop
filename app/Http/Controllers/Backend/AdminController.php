<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Service\FileWorkerInterface;

class AdminController extends Controller {

    protected $user;
    protected $adresse;
    protected $file;

    public function __construct(UserInterface $user, AdresseInterface $adresse, FileWorkerInterface $file)
    {
        $this->user    = $user;
        $this->adresse = $adresse;
        $this->file    = $file;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $files = $this->file->manager();

        return view('backend.index')->with(['files' => $files]);
	}
}
