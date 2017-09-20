<?php

namespace App\Http\Controllers\Auth;

use App\Droit\User\Entities\User;
use App\Droit\Adresse\Repo\AdresseInterface;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/pubdroit';
    protected $adresse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdresseInterface $adresse)
    {
        $this->middleware('guest');
        
        $this->adresse = $adresse;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|confirmed|min:6',
            'my_name'  => 'honeypot',
            'my_time'  => 'required|honeytime:5'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $format  = new \App\Droit\Helper\Format();

        return User::create([
            'first_name' => $format->formatingName($data['first_name']),
            'last_name'  => $format->formatingName($data['last_name']),
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $this->guard()->login($this->create($request->all()));

        \Auth::user()->adresses()->save($this->adresse->create($request->all()));

        return redirect($this->redirectPath());
    }
}
