<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\User\Repo\UserInterface;

class ValidationController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Check email exist via ajax validation
     *
     * @return boolean
     */
    public function check(Request $request)
    {
        $exist = $this->user->findByEmail($request->input('email'));

        if($exist)
        {
            return response()->json(['Cet email est déjà utilisé, veuillez en utiliser un autre']);
        }

        return response()->json("true");
    }

}
