<?php

namespace App\Http\Controllers\Backend\User;

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
        $user = $this->user->create($request->all());

        alert()->success('Utilisateur crée');

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
        $user = $this->user->find($id);
        
        return view('backend.users.show')->with(compact('user'));
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

        alert()->success('Utilisateur mis à jour');

        return redirect()->back();
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
        $this->user->restore($id);

        alert()->success('Utilisateur restauré');

        return redirect()->back();
    }
}
