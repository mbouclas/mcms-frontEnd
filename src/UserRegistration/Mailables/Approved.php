<?php

namespace Mcms\FrontEnd\UserRegistration\Mailables;

use Config;
use Mcms\FrontEnd\UserRegistration\SendMailViaConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Lang;

class Approved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $model;

    public function __construct($id)
    {
        if ( ! is_object($id)){
            $userModel = Config::get('auth.providers.users.model');
            $this->model = new $userModel();
            $this->user = $this->model->find($id);
            return $this;
        }

        $this->user = $id;

        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = Config::get('frontEnd.user.mailables.approved');
        $mailer = new SendMailViaConfig();
        $from = $mailer->formAdminUser();

        return $this
            ->subject(Lang::get($config['subject'], $mailer->sanitizeModelToArray($this->user)))
            ->with('user', $this->user)
            ->from($from['address'], $from['name'])
            ->view($config['view']);
    }
}