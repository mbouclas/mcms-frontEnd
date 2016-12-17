<?php

namespace Mcms\FrontEnd\UserRegistration;


use Mcms\Core\Services\User\UserService;
use Mcms\FrontEnd\Exceptions\InvalidConfirmationCodeException;

class ValidateConfirmationCode
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function handle($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user = $this->userService->model->whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            throw new InvalidConfirmationCodeException;
        }

        return $user;
    }
}