<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Illuminate\Console\Command;


/**
 * @example php artisan vendor:publish --provider="Mcms\FrontEnd\FrontEndServiceProvider" --tag=config
 * Class PublishSettings
 * @package Mcms\FrontEnd\Console\Commands\InstallerActions
 */
class PublishSettings
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['config'],
        ]);

        $command->comment('Settings published');
    }
}