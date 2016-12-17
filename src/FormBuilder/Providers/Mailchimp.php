<?php

namespace Mcms\FrontEnd\FormBuilder\Providers;


use Mcms\FrontEnd\FormBuilder\Contracts\FormBuilderProvider;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Mailchimp implements FormBuilderProvider
{
    public $label = 'Mailchimp';
    public $description = 'Sends all the form data to mailchimp';
    public $comments = [
        [
            'type' => 'danger',
            'message' => 'You need a field called email or else Mailchimp will not work',
            'whenNot' => 'email'
        ],
        [
            'type' => 'info',
            'message' => 'Tip! Add the fields FNAME & LNAME to register first & last name to Mailchimp'
        ]
    ];
    public $varName = 'mailchimp';
    public $config = [
       [
           'type' => 'text',
           'label' => 'List ID',
           'varName' => 'list_id',
           'required' => true
       ],
        [
            'type' => 'text',
            'label' => 'List name',
            'varName' => 'list_name',
            'required' => true
        ],
        [
            'type' => 'boolean',
            'label' => 'Track user location',
            'varName' => 'track_location',
            'default' => false
        ],
    ];

    public $settings = [

    ];

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
            'settings' => $this->settings,
            'class' => get_class($this),
            'comments' => $this->comments,
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
     * @return mixed
     */
    public function validate(array $fieldValues)
    {
        // TODO: Implement validate() method.
    }

    /**
     * @return mixed
     */
    public function process(Request $request, FormBuilder $form)
    {
        if ( ! class_exists(\Mcms\Mailchimp\Service\MailchimpService::class)) {
            return false;
        }

        if ( ! $request->has('email')) {
            return false;
        }

        $mc = new \Mcms\Mailchimp\Service\MailchimpService(\Mcms\Mailchimp\Service\MailchimpListCollection::createFromString($form->meta['providerConfig']['mailchimp']['list_id'], $form->meta['providerConfig']['mailchimp']['list_name']));
        $mc->subscribe($request->email, $request->toArray());

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