<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;

use Validator;
use Socialite;
use App\Droit\User\Entities\User;
use App\Droit\Adresse\Repo\AdresseInterface;

class AuthController extends Controller {

    protected $redirectPath = '/';

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $adresse;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct(AdresseInterface $adresse)
	{
		$this->middleware('guest', ['except' => 'getLogout']);

        $this->adresse = $adresse;
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

        $returnPath = $request->input('returnPath',null);

        $roles = $user->roles->lists('id')->all();

        // Logic that determines where to send the user
        if (in_array(1,$roles))
        {
            return redirect()->intended('admin');
        }

        if($returnPath)
        {
            return redirect($returnPath);
        }

        return redirect()->intended('profil');
    }

    /**
     * Redirect user role
     *
     * @return string
     */
    public function redirectPath()
    {
        $user = \Auth::user();
        $user->load('roles');

        $roles = $user->roles->lists('id')->all();

        // Logic that determines where to send the user
        if (!in_array(1,$roles))
        {
            return '/admin';
        }

        return '/profil';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
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
        $validator = $this->validator($request->all());

        if ($validator->fails())
            $this->throwValidationException($request, $validator);

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        Auth::user()->adresses()->save($this->adresse->create($request->all()));

        return redirect($this->redirectPath());
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return trans('message.fail_login');
    }

    /**
     * Redirect the user to the Droitformation authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('droithub')->redirect();
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBeta()
    {
        return view('auth.beta');
    }

}
