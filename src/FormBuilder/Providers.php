<?php

namespace Mcms\FrontEnd\FormBuilder;


use Mcms\FrontEnd\FormBuilder\Contracts\FormBuilderProvider;
use Illuminate\Support\Collection;

class Providers
{
    protected $providers;

    public function __construct()
    {
        $this->providers = new Collection();
    }

    public function load()
    {
        $providers = ( ! \Config::has('formBuilder.providers')) ? [] : \Config::get('formBuilder.providers');

        foreach ($providers as $provider) {
            $this->register((new $provider)->register());
        }

        return $this;
    }

    public function register(Collection $provider)
    {
        $this->providers->push($provider);

        return $this;
    }

    public function get()
    {
        return $this->providers;
    }
}