<?php

namespace Mcms\FrontEnd\UserRegistration;


use Config;
use Mcms\Core\Models\User;
use Mcms\Core\Services\User\UserService;

/**
 * This behaves like the Verified one but with it also notifies the admin and does not activates the user automatically
 * The admin will do that
 *
 * Class ModeratedAfter
 * @package Mcms\FrontEnd\UserRegistration
 */
class ModeratedAfter
{
    protected $userService;
    protected $mailer;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->mailer = new SendMailViaConfig();
    }

    /**
     * @param $user
     */
    public function handle(User $user)
    {
        $verifiedAction = new VerifiedAfter();
        $verifiedAction->handle($user);
    }

    public function onVerify($confirmation_code)
    {
        $user = (new ValidateConfirmationCode())->handle($confirmation_code);

        $user->active = false;
        $user->awaits_moderation = true;
        $user->confirmation_code = null;
        $user->save();

        $config = Config::get('frontEnd.user.mailables');

        //notify the admin
        if ( ! isset($config['NotifyAdminOnNewUser'])) {
            $this->userService->notifyAdminOnNewUser($user);
            return $user;
        }

        $this->mailer->send('frontEnd.user.mailables.NotifyAdminOnNewUser', $user);
        $this->mailer->send('frontEnd.user.mailables.welcome', $user);

        return $user;
    }
}