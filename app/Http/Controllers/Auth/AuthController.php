<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Validator;
use Socialite;
use App\Droit\User\Entities\User;

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

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
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
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
/*    public function handleProviderCallback()
    {
        $user = Socialite::driver('droithub')->user();

        // stroing data to our use table and logging them in
        $data = [
            'name'  => $user->getName(),
            'email' => $user->getEmail()
        ];

        Auth::login(\App\Droit\User\Entities\User::firstOrCreate($data));

        //after login redirecting to home page
        return redirect($this->redirectPath());
    }*/

}
