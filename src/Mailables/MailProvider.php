<?php

namespace Mcms\FrontEnd\Mailables;


use Mcms\Core\Models\User;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailProvider extends Mailable
{
    use Queueable, SerializesModels;

    public $body;
    public $form;
    public $toUser;

    public function __construct(Request $request, FormBuilder $form, array $to)
    {
        $this->body = $request->all();
        $this->form = $form;
        $this->toUser = $to;
    }

    public function build()
    {
        $from = [
            'email' => (isset($this->form->meta['providerConfig']['mail']['email'])) ? $this->form->meta['providerConfig']['mail']['email'] : \Config::get('mail.from.email'),
            'name' => (isset($this->form->meta['providerConfig']['mail']['name'])) ? $this->form->meta['providerConfig']['mail']['name'] : \Config::get('mail.from.name'),
        ];

        $siteName = \Config::get('core.siteName');

        return $this
            ->to($this->toUser['email'], $this->toUser['name'])
            ->subject((isset($this->form->meta['providerConfig']['mail']['subject'])) ? $this->form->meta['providerConfig']['mail']['subject'] : "New email from {$siteName}")
            ->from($from['email'], $from['name'])
            ->view('emails.notifications.contactForm');
    }
}