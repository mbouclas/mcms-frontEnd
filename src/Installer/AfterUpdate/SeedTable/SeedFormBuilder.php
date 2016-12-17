<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate\SeedTable;


use Illuminate\Console\Command;
use Mcms\FrontEnd\Console\Commands\InstallerActions\Seeders\FormBuilderSeed;
use Mcms\FrontEnd\Models\FormBuilder as FormBuilderModel;

class SeedFormBuilder
{
    public function handle(Command $command)
    {
        $model = new FormBuilderModel();
        $sample = $model->where('slug', 'contact-form')->first();
        if ( ! $sample){
            (new FormBuilderSeed())->handle();
        }
    }
}