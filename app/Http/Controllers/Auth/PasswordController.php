<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller {

    protected $redirectPath = '/';

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

    /**
     * Display the password new form
     *
     * @return Response
     */
    public function getNew()
    {
        return view('auth.new');
    }

    /**
     * Reset the given user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postDefine(Request $request)
    {
        $v = \Validator::make($request->all(), [
            'email'        => 'required|email',
            'password'     => 'required|different:old_password|min:6',
            'old_password' => 'required',
        ],[ 'password.different' => 'Le champ nouveau mot de passe doit être différent du mot de passe actuel']);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $email        = $request->input('email');
        $password     = $request->input('password');
        $old_password = $request->input('old_password');

        if (\Auth::attempt(['email' => $email, 'password' => $old_password]))
        {
            $user = \Auth::user();
            $user->password = bcrypt($password);
            $user->save();
            \Auth::login($user);

            return redirect('user')->with(['status' => 'success', 'message' => 'Votre mot de passe a bien été changé' ]);
        }

        return redirect()->back()->with(['status' => 'danger', 'message' => 'Les identifiants email / mot de passe ne correspondent pas'])->withInput($request->only('email'));
    }
}
