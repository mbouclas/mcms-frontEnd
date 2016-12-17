<?php

namespace Mcms\FrontEnd\Models;

use Config;
use Mcms\Core\QueryFilters\Filterable;
use Mcms\FrontEnd\FormBuilder\FormBuilderService;
use Mcms\FrontEnd\FormBuilder\Providers;
use Mcms\FrontEnd\Helpers\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Themsaid\Multilingual\Translatable;

class FormBuilder extends Model
{
    use Translatable, Filterable, Sluggable;

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['title', 'slug', 'provider', 'label', 'description', 'fields', 'settings', 'meta'];

    public $casts = [
        'label' => 'array',
        'provider' => 'array',
        'description' => 'array',
        'fields' => 'array',
        'settings' => 'array',
        'meta' => 'array',
    ];

    public $translatable = ['label', 'description'];
    public $config;
    public $route;
    protected $defaultRoute = 'formBuilder-post';
    public $providers;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->config = Config::get('formBuilder');
        $this->providers = new Collection();

        $this->defaultRoute = $this->config['route']['config']['as'];
    }

    public function providers($providersList = null)
    {
        $providersList = ( ! $providersList) ? $this->provider : $providersList;
        $service = new Providers();
        $providers = $service->load()->get()->whereIn('varName', $providersList);

        foreach ($providers as $provider) {
            $this->providers->push(new $provider['class']);
        }

        return $this->providers;
    }
}
