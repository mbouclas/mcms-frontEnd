<?php

namespace Mcms\FrontEnd\Http\Controllers\Auth;

use Config;
use Mcms\Core\Models\User;
use Mcms\Core\Services\User\UserService;
use Mcms\FrontEnd\Exceptions\InvalidConfirmationCodeException;
use Mcms\FrontEnd\Services\UserRegistration;
use Illuminate\Http\Request;
use Validator;
use Mcms\FrontEnd\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

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
    protected $redirectTo = '/login';

    protected $userService;

    protected $userRegistrationService;

    protected $defaultRegisterValidatorRules = [
        'firstName' => 'required|max:255',
        'lastName' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['verify']]);
        $this->middleware('web');
        $this->userService = new UserService();
        $this->userRegistrationService = new UserRegistration();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = (Config::has('config.user.register.validator'))
            ? Config::get('config.user.register.validator')
            : $this->defaultRegisterValidatorRules;

        return Validator::make($data, $rules);
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

        $newUser = $this->create($request->all());

        return redirect($this->redirectPath())
            ->with(['user' => $newUser, 'registered' => true]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $user
     * @return User
     */
    protected function create(array $user)
    {
        $this->userRegistrationService->before($user);
        $newUser = $this->userService->store($user);
        $this->userRegistrationService->after($newUser);

        return $newUser;
    }

    public function test()
    {

    }

    public function verify($code)
    {
        if (Config::has('frontEnd.user.register.after')){

            //we need to handle this registration
            try {
                $return = $this->userRegistrationService->onVerify($code);
                if ( ! $return){
                    return view('errors.invalid_confirmation_code')->withErrors(['message' => 'invalidConfirmationCode']);
                }
            }
            catch (InvalidConfirmationCodeException $e){
                return view('errors.invalid_confirmation_code')->withErrors($e->getMessage());
            }


            return redirect($this->redirectPath())
                ->with(['user' => $return, 'verified' => true]);
        }

        return redirect()
            ->route('login')
            ->with(['verified' => true]);
    }
}
