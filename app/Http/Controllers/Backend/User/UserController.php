<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Droit\User\Worker\AccountWorkerInterface;
use App\Droit\Newsletter\Worker\SubscriptionWorkerInterface;
use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;

class UserController extends Controller {

    protected $user;
    protected $account;
    protected $subscription_worker;

    public function __construct(UserInterface $user, AccountWorkerInterface $account, SubscriptionWorkerInterface $subscription_worker)
    {
        $this->user = $user;
        $this->account = $account;
        $this->subscription_worker = $subscription_worker;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request,$back = null)
    {
        if($back){
            $search = session()->get('user_search');
            $term   = isset($search['term']) && !empty($search['term']) ? $search['term'] : null;
        }
        else{
            $term = $request->input('term',null);
            session(['user_search' => ['term' => $term]]);
        }
        
        $users = $term ? $this->user->search($term) : $this->user->getAll();

        return view('backend.users.index')->with(['users' => $users, 'term' => $term]);
    }

    public function users(Request $request)
    {
        $order  = $request->input('order');
        $search = $request->input('search',null);
        $search = ($search ? $search['value'] : null);

        return $this->user->get_ajax(
            $request->input('draw'), $request->input('start'), $request->input('length'), $order[0]['column'], $order[0]['dir'], $search
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateUser $request)
    {
        $user = $this->user->create(array_filter($request->all()));

        flash('Utilisateur crée')->success();

        return redirect()->to('admin/user/'.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $user    = $this->user->find($id);
        $account = new \App\Droit\User\Entities\Account($user);

        if(isset($_GET['path'])){
            session(['return_path' => ['user_'.$id => redirect()->getUrlGenerator()->previous()]]);
        }

        return view('backend.users.show')->with(['user' => $user, 'account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,UpdateUser $request)
    {
        $user = $this->user->update(array_filter($request->all()));

        $request->ajax();

        flash('Utilisateur mis à jour')->success();

        return redirect('admin/user/'.$id);
    }

    public function confirm($id)
    {
        $user = $this->user->find($id);

        return view('backend.users.confirm')->with(compact('user'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $user = $this->user->find($id);

        if($request->input('confirm') && !empty($request->input('newsletter_id'))){
            $subscriber = $this->subscription_worker->exist($user->email);
            $this->subscription_worker->unsubscribe($subscriber,$request->input('newsletter_id'));
        }
        else{
            $validator = new \App\Droit\User\Worker\UserValidation($user);
            $validator->activate();
        }

        $this->user->delete($id);

        flash('Utilisateur supprimé')->success();

        return redirect('admin/search/user');
    }

    /**
     * Restore the user
     *
     * @param  int  $id
     * @return Response
     */
    public function restore($id)
    {
        $this->account->restore($id);

        flash('Utilisateur restauré')->success();

        return redirect()->back();
    }

    public function unsubscribe(Request $request)
    {
        $subscriber = $this->subscription_worker->exist($request->input('email'));
        $this->subscription_worker->unsubscribe($subscriber,[$request->input('newsletter_id')]);

        flash('Abonnement à la newsletter supprimé')->success();

        return redirect()->back();
    }
}
