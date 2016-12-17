<?php

namespace Mcms\FrontEnd\UserRegistration;

use Mcms\Core\Models\User;


/**
 * This sends an email to user after the registration is complete. The email contains a confirmation key.
 * If the user clicks the link, he is then verified and activated
 *
 * Class VerifiedAfter
 * @package Mcms\FrontEnd\UserRegistration
 */
class VerifiedAfter
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new SendMailViaConfig();
    }

    /**
     * @param $user
     */
    public function handle(User $user)
    {
        //send email
        $this->mailer->send('frontEnd.user.mailables.activation', $user);


    }

    public function onVerify($confirmation_code)
    {
        $user = (new ValidateConfirmationCode())->handle($confirmation_code);

        $user->active = true;
        $user->awaits_moderation = false;
        $user->confirmation_code = null;
        $user->save();

        //mail user if welcome is set
        $this->mailer->send('frontEnd.user.mailables.welcome', $user);


        return $user;
    }
}