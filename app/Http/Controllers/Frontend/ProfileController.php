<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\CreateAdresse;
use App\Http\Requests\UpdateAdresse;
use App\Http\Requests\UpdateUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    protected $adresse;
    protected $user;
    protected $format;

    public function __construct(AdresseInterface $adresse, UserInterface $user)
    {
        $this->adresse = $adresse;
        $this->user    = $user;
        $this->format  = new \App\Droit\Helper\Format();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user    = $this->user->find(\Auth::user()->id);
        $current = '/';

        return view('frontend.pubdroit.profil.account')->with(compact('user','current'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateAdresse $request)
    {
        $data = $request->all();

        if(!empty($data))
        {
            if(isset($data['id']))
            {
                $this->adresse->update($data);
            }
            else
            {
                $this->adresse->create($data);
            }
        }

        return redirect('profil')->with(['status' => 'success', 'message' => 'Adresses mise à jour']);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function orders()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.profil.orders')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function colloques()
    {
        $user = $this->user->find(\Auth::user()->id);

        return view('frontend.pubdroit.profil.inscriptions')->with(compact('user'));
    }

    /**
     *
     * @return Response
     */
    public function inscription($id)
    {
        $user = $this->user->find(\Auth::user()->id);
        $inscription = $user->inscriptions->find($id);
        $inscription->load('user_options','colloque');
        $inscription->user_options->load('option');
        $inscription->colloque->load('location','centres','compte');

        return view('frontend.pubdroit.profil.inscription')->with(compact('user','id','inscription'));
    }

}
