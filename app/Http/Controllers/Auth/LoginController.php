<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Droit\User\Entities\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the admin login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdmin()
    {
        return view('auth.login')->with(['admin' => true]);
    }

    public function authenticated(Request $request, User $user )
    {
        $user->load('roles');

        $roles = $user->roles->pluck('id')->all();

        $path = session()->pull('url.intended');

        // Logic that determines where to send the user
        if (in_array(1,$roles)) {
            $returnPath = $path ? $path : 'admin';
            return redirect()->intended($returnPath);
        }

        if($path) {
            return redirect($path);
        }

        return redirect()->intended('pubdroit/profil');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
            'my_name'  => 'honeypot',
            'my_time'  => 'required|honeytime:1'
        ]);
    }
}
