<?php

namespace App\Http\Controllers\Backend\User;

use App\Droit\Colloque\Repo\ColloqueInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Inscription\Repo\RabaisInterface;

class RabaisUserController extends Controller {

    protected $rabais;
    protected $user;

    public function __construct( RabaisInterface $rabais, UserInterface $user)
    {
        $this->rabais   = $rabais;
        $this->user     = $user;
    }

    public function add(Request $request)
    {
        $user   = $this->user->find($request->input('id'));
        $rabais = $this->rabais->find($request->input('rabais_id'));

        $user->rabais()->attach($rabais->id);

        flash('Rabais ajouté')->success();

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $user   = $this->user->find($request->input('id'));
        $rabais = $this->rabais->find($request->input('rabais_id'));

        if($rabais){
            $user->rabais()->detach($rabais->id);
        }

        flash('Rabais retiré')->success();

        return redirect()->back();
    }

}
