<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingConfig;


use Illuminate\Console\Command;

class PublishFormBuilderConfig
{
    public function handle(Command $command)
    {
        $file = 'formBuilder.php';
        $targetFile = config_path("{$file}");
        if ( ! \File::exists($targetFile)){
            \File::copy(__DIR__ . "/../../../../config/{$file}", $targetFile);
        }
    }
}