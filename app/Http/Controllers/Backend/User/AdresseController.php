<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;

class AdresseController extends Controller {

    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->format  = new \App\Droit\Helper\Format();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $adresses = $this->adresse->getAll();

        return view('backend.adresses.index')->with([ 'adresses' => $adresses ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.adresses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateAdresse $request)
    {
        $adresse = $this->adresse->create($request->all());

        return redirect('adresse/'.$adresse->id);
    }


    public function convert(Request $request)
    {
        $adresse = $this->adresse->find($request->input('id'));

        $data = [
            'first_name' => $adresse->first_name,
            'last_name'  => $adresse->last_name,
            'email'      => $adresse->email,
            'password'   => bcrypt($request->input('password')),
        ];

        $validator = \Validator::make($data, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:5',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $this->user->create($data);

        $this->adresse->update(['id' => $adresse->id, 'user_id' => $user->id, 'livraison' => 1]);

        return redirect('admin/user/'.$user->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $adresse = $this->adresse->find($id);

        if($request->ajax())
        {
            return $adresse->load('canton','profession','specialisations','civilite');
        }

        return view('backend.adresses.show')->with(array( 'adresse' => $adresse ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,UpdateAdresse $request)
    {
        $adresse = $this->adresse->update($request->all());

        return redirect()->back()->with(['status' => 'success', 'message' => 'Adresse mise à jour']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $this->adresse->delete($id);

        $url = $request->input('url',null);
        $url = $url ? $url : 'admin';

        return redirect($url)->with(array('status' => 'success', 'message' => 'Adresse supprimé' ));
    }

}
