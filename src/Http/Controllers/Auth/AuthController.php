<?php

namespace Mcms\FrontEnd\Http\Controllers\Auth;

use Hash;
use Mcms\Core\Models\User;
use Mcms\FrontEnd\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Lang;
use Validator;

class AuthController extends Controller
{
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('web');
        $this->middleware('guest', ['except' => 'logout']);
        $this->redirectTo = (\Config::has('frontEnd.user.login.redirectTo'))
            ? \Config::has('frontEnd.user.login.redirectTo')
            : $this->redirectTo;
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout', 'getLogout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
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
        return User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        //add roles if any
    }

    protected function credentials(Request $request)
    {
        $request->merge(['active' => true]);

        return $request->only($this->username(), 'password', 'active');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                'active' => Lang::get('auth.inActive'),
                $this->username() => Lang::get('auth.failed'),
            ]);
    }
}