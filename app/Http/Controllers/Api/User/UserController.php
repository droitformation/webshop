<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;

class UserController extends Controller {

    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->user->getAll();

        return view('backend.users.index')->with([ 'users' => $users ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateUser $request)
    {
        $user = $this->user->create($request->all());

        return redirect('user/'.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $user = $this->user->find($id);

        if($request->ajax())
        {
            return response()->json($user);
        }

        return view('users.show')->with(array( 'user' => $user ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,UpdateUser $request)
    {
        $user = $this->user->update($request->all());

        $request->ajax();

        return redirect('user/'.$user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->user->delete($id);

        alert()->success('Utilisateur supprimé');

        return redirect('/');
    }

    public function getAdresse($id)
    {
        $user    = $this->user->find($id);
        $adresse = $user->adresse_facturation ? $user->adresse_facturation : '';

        return $adresse;
        die();
    }

    public function getUser($id)
    {
        $user    = $this->user->find($id);

        return [
            'user_id'  => $id,
            'civilite' => $user->adresse_contact->civilite_title,
            'name'     => $user->adresse_contact->name ,
            'company'  => $user->adresse_contact->company,
            'cp'       => $user->adresse_contact->cp_trim,
            'adresse'  => $user->adresse_contact->adresse,
            'npa'      => $user->adresse_contact->npa,
            'ville'    => $user->adresse_contact->ville,
        ];

        die();
    }
}
