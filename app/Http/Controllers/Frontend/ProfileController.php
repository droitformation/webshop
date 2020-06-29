<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\User\Repo\UserInterface;
use App\Http\Requests\UpdateAdresse;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    protected $adresse;
    protected $colloque;
    protected $user;
    protected $newsworker;

    public function __construct(AdresseInterface $adresse, ColloqueInterface $colloque, UserInterface $user)
    {
        $this->adresse    = $adresse;
        $this->colloque   = $colloque;
        $this->user       = $user;
        $this->newsworker = \App::make('newsworker');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('frontend.pubdroit.profil.account')->with(['current' => '/', 'user' => \Auth::user()]);
    }

    public function account(Request $request)
    {
        $this->user->update($request->all());

        $request->session()->flash('updateAdresse', 'Adresse mise à jour');

        return redirect('pubdroit/profil');
    }

    public function update(UpdateAdresse $request)
    {
        $data = $request->all();

        if(!empty($request->all())) {

            $action = $request->input('id',null) ? 'update' : 'create';

            // Update or create
            $this->adresse->$action($data);

            // Change livraison adresse
            if($request->input('id',null) && $data['livraison'] > 0){
                $this->adresse->changeLivraison($request->input('id') , $request->input('user_id'));
            }
        }

        $request->session()->flash('updateAdresse', 'Adresse mise à jour');

        return redirect('pubdroit/profil');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function orders()
    {
        return view('frontend.pubdroit.profil.orders')->with(['user' => \Auth::user()]);
    }
    
    public function colloques()
    {
        return view('frontend.pubdroit.profil.inscriptions')->with(['user' => \Auth::user()]);
    }
    
    public function inscription($id)
    {
        $inscription = \Auth::user()->inscriptions->find($id);

        return view('frontend.pubdroit.profil.inscription')->with(['user' => \Auth::user(), 'id' => $id, 'inscription' => $inscription]);
    }

    public function abos()
    {
        return view('frontend.pubdroit.profil.abos')->with(['user' => \Auth::user()]);
    }

    public function book($colloque_id,$media_id)
    {
        $colloque   = $this->colloque->find($colloque_id);
        $mediaItems = $colloque->getMedia('preview');
        $media      = $mediaItems->find($media_id);

        return view('frontend.pubdroit.profil.book')->with(['media' => $media]);
    }

    public function subscriptions()
    {
        $emails = array_merge([\Auth::user()->email], isset(\Auth::user()->adresses) ? \Auth::user()->adresses->pluck('email')->all() : []);

        $subscriptions = $this->newsworker->hasSubscriptions(array_unique($emails));
        $subscriptions = $subscriptions->groupBy('email')->map(function($subscription,$key){
            return $subscription->pluck('subscriptions')->flatten(1)->unique('list_id');
        });

        return view('frontend.pubdroit.profil.subscription')->with(['user' => \Auth::user(), 'subscriptions' => $subscriptions]);
    }

}
