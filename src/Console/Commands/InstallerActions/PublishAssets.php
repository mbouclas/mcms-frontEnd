<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Illuminate\Console\Command;

/**
 * Class PublishAssets
 * @package Mcms\FrontEnd\Console\Commands\InstallerActions
 */
class PublishAssets
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['public'],
        ]);

        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['assets'],
        ]);

        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['admin-package'],
        ]);

        $command->call('vendor:publish', [
            '--tag' => ['laravel-notifications'],
        ]);


        $command->comment('Assets published');
    }
}