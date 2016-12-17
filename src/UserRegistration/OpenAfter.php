<?php

namespace Mcms\FrontEnd\UserRegistration;


use Mcms\Core\Models\User;

/**
 * This activates the user right away
 *
 * Class OpenAfter
 * @package Mcms\FrontEnd\UserRegistration
 */
class OpenAfter
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new SendMailViaConfig();
    }

    /**
     * @param User $user
     */
    public function handle(User $user)
    {
        $user->active = true;
        $user->awaits_moderation = false;
        $user->confirmation_code = null;
        $user->save();

        //mail user if welcome is set
        $this->mailer->send('frontEnd.user.mailables.welcome', $user);


        return $user;
    }
}