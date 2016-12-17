<?php

namespace FrontEnd\Http\Controllers\Auth;

use Mcms\FrontEnd\Http\Controllers\Auth\AuthController as BaseAuthController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends BaseAuthController
{
    use AuthenticatesUsers;
}