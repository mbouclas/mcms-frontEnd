<?php

namespace Mcms\FrontEnd\FormBuilder\Providers;


use Mcms\Core\Models\User;
use Mcms\FrontEnd\FormBuilder\Contracts\FormBuilderProvider;
use Mcms\FrontEnd\FormBuilder\Validators\BaseProviderValidator;
use Mcms\FrontEnd\Mailables\MailProvider;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mail as Mailer;

class Mail implements FormBuilderProvider
{
    public $label = 'Mail';
    public $description = 'Sends all the form data as email';
    public $varName = 'mail';
    public $config = [
        [
            'type' => 'email',
            'label' => 'Email address',
            'varName' => 'email',
            'required' => true,
            'validator' => 'required|email'
        ],
        [
            'type' => 'text',
            'label' => 'Recipient name',
            'varName' => 'name',
            'required' => true,
            'validator' => 'required'
        ],
        [
            'type' => 'text',
            'label' => 'Subject',
            'varName' => 'subject',
            'required' => true,
            'validator' => 'required'
        ],
    ];
    public $mailable = MailProvider::class;

    /**
     * @return Collection
     */
    public function register()
    {
        return new Collection([
            'label' => $this->label,
            'description' => $this->description,
            'varName' => $this->varName,
            'config' => $this->config,
            'class' => get_class($this),
        ]);
    }

    /**
     * @return mixed
     */
    public function controller()
    {
        // TODO: Implement controller() method.
    }

    /**
     * @return mixed
     */
    public function url()
    {
        // TODO: Implement url() method.
    }

    /**
     * @return mixed
     */
    public function isValid()
    {
        // TODO: Implement isValid() method.
    }

    /**
     * @return boolean
     */
    public function validate(array $fieldValues)
    {
        return (new BaseProviderValidator($this->config))->check($fieldValues);
    }

    /**
     * @return mixed
     */
    public function process(Request $request, FormBuilder $form)
    {
        $to = [
            'email' => (isset($form->meta['providerConfig']['mail']['email'])) ? $form->meta['providerConfig']['mail']['email'] : \Config::get('mail.from.email'),
            'name' => (isset($form->meta['providerConfig']['mail']['name'])) ? $form->meta['providerConfig']['mail']['name'] : \Config::get('mail.from.name'),
        ];

        $message = (new MailProvider($request, $form, $to))
            ->onQueue('emails');

        Mailer::queue($message);

        return true;
    }

    /**
     * @return mixed
     */
    public function result()
    {
        // TODO: Implement result() method.
    }


}