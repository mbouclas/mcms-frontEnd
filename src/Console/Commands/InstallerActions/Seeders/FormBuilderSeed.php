<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions\Seeders;

use Mcms\FrontEnd\Models\FormBuilder as FormBuilderModel;

class FormBuilderSeed
{
    public function handle()
    {
        $model = new FormBuilderModel();
        $languages = \LaravelLocalization::getSupportedLanguagesKeys();
        $label = [];
        $description = [];
        foreach ($languages as $language){
            $label[$language] = 'Contact us';
            $description[$language] = 'Use this contact form';
        }

        $model->create([
            'title' => 'Contact form',
            'slug' => 'contact-form',
            'provider' => '',
            'label' => $label,
            'description' => $description,
            'fields' => [],
            'settings' => [],
            'meta' => []
        ]);
    }
}