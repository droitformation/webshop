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
        $adresses = collect([]);
        $terms    = [];

        $type     = !empty($request->input('type')) ? $request->input('type') : 'user';
        $group    = !empty($request->input('group')) &&  $request->input('group') != 'user_id' ? $request->input('group') : '';
        $operator = !empty($request->input('operator')) ? $request->input('operator') : 'and';

        if(!empty($request->all())){

            $terms    = $this->worker->prepareTerms($request->only('terms','columns'),$type);

            if(empty($terms)){
                $request->flash();
                alert()->danger('Les termes ne correspondent pas au type de recherche');
                return redirect()->back();
            }

            $adresses = $this->$type->getDeleted($terms, $operator);

            if($type == 'adresse'){

                list($onlyadresse, $hasuser) = $adresses->partition(function ($adresse) {
                    return !$adresse->user_id;
                });

                $hasuser      = $hasuser->sortBy('id')->unique('user_id');
                $onlyadresse  = $onlyadresse->unique('id');

                $adresses = $onlyadresse->merge($hasuser);
            }

            $adresses = $adresses->sortBy('name_slug')->groupBy($group);
        }

        return view('backend.deleted.index')->with([
            'adresses' => $adresses, 'terms' => $terms, 'type' => $type, 'group' => $group, 'operator' => $operator
        ]);
    }

    /*
    * Get particular adresse
    * */
    public function compare(Request $request)
    {
        $ids = $request->input('adresses',session()->get('adresses'));

        session(['adresses' => $ids]);
        
        $adresses = $this->adresse->getMultiple($ids);

        return view('backend.deleted.compare')->with(['adresses' => $adresses]);
    }

    /*
    * transvase
    * */
    public function transvase(Request $request)
    {

        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        // Adresses ids and transvase recipient id (an adresse id to)
        $adresses_ids = $request->input('ids');
        $transvase_id = $request->input('transvase_id',null);
        $adresses_ids = explode(',',$adresses_ids);

        // If there is a adresse for recipient and it is a user
        if(!$transvase_id){
            $request->flash();
            alert()->danger('Oho! Il manque une adresse/compte pour transvaser vers.');
            return redirect()->back();
        }

        $recipient = $this->adresse->find($transvase_id);

        // no user? redirect
        if(!$recipient || !isset($recipient->user)){
            alert()->danger('Aucun utilisateur pour accrocher les éléments');
            return redirect('admin/deletedadresses');
        }

        $worker->setFromAdresses([$adresses_ids])
            ->setAction($request->input('action'))
            ->setTypes($request->input('types'))
            ->removeSession()
            ->reassignFor($recipient->user);

        session(['transvase' => $request->all()]);
        
        alert()->success('Transfert terminé!');
        
        return redirect('admin/deletedadresses/result');
    }

    /*
     * Get particular adresse
     * */
    public function result()
    {
        $transvase = session()->get('transvase', []);

        if(empty($transvase)){
            alert()->danger('Aucune info trouvé sur le transfert :(');
            return redirect('admin/deletedadresses');
        }

        $transvase_id = $transvase['transvase_id'];
        $adresses_ids = explode(',',$transvase['ids']);
        $types        = isset($transvase['types']) ? $transvase['types'] : [];
        $deleted      = array_filter(session()->get('deleted',[]));

        $deleted = collect($deleted)->map(function ($ids, $key) {
            return $this->$key->getMultiple($ids);
        });

        $adresses  = $this->adresse->getMultiple($adresses_ids);

        $recipient = $this->adresse->find($transvase_id);

        return view('backend.deleted.result')->with([
            'adresses' => $adresses, 'recipient' => $recipient, 'action' => $transvase['action'], 'types' => $types, 'deleted' => $deleted
        ]);
    }

    /*
     * Restore particular adresse
     * */
    public function restoreAdresse(Request $request)
    {
        $id      = $request->input('id');
        $user_id = $request->input('user_id');
        $partial = 'adresse';

        // Restore an account
        if($request->input('type') == 'user'){
            $user = $this->user->restore($user_id);
            $partial = 'user';
        }
        else{
            // Restore an adresse and redirect
            $adresse = $this->adresse->restore($id);

            // Redirect to an account row else to an adresse row
            if($user_id){
                $user = $this->user->findWithTrashed($user_id);
                $partial = 'user';
            }
            else{
                $adresse = $this->adresse->restore($id);
            }
        }

        echo view('backend.deleted.partials.'.$partial.'-row')->with(['adresse' => isset($adresse) ? $adresse : null, 'user' => isset($user) ? $user : null]);
    }

    /*
     * Delete particular adresse and assign abos and/or order to main user
     * Set from adresses
     * Set types
     * */
    public function removeAdresse(Request $request)
    {
        $user_id = $request->input('user_id');
        $id      = $request->input('id');

        $worker = \App::make('App\Droit\Adresse\Worker\AdresseWorkerInterface');

        $user    = $this->user->findWithTrashed($user_id);
        $adresse = !$user->adresses->isEmpty() ? $user->adresses->first() : null;

        if(!$adresse){
            throw new \App\Exceptions\AdresseRemoveException('Aucune adresse pour accrocher les éventuels abonnement');
        }

        $worker->setFromAdresses([$id])->setAction('delete')->setTypes(['orders','abos'])->reassignFor($user, false); // no comparing

        echo view('backend.deleted.partials.user-row')->with(['adresse' => $adresse, 'user' => $user]);
    }

    /*
      * Delete particular adresse and assign abos and/or order to main user
      * Set from adresses
      * Set types
      * */
    public function removeAdresseRow(Request $request)
    {
        $this->adresse->delete($request->input('id'));
        $adresse = $this->adresse->findWithTrashed($request->input('id'));

        echo view('backend.deleted.partials.adresse-row')->with(['adresse' => $adresse->fresh()]);
    }

    /*
      * Delete particular user
      * */
    public function removeUser(Request $request)
    {
        $this->user->delete($request->input('user_id'));
        $user = $this->user->findwithtrashed($request->input('user_id'));
        $adresse = isset($user->adresses) && !$user->adresses->isEmpty() ? $user->adresses->first() : null;

        echo view('backend.deleted.partials.user-row')->with(['user' => $user->fresh(), 'adresse' => $adresse]);
    }
}
