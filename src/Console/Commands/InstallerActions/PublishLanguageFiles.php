<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Illuminate\Console\Command;

/**
 * Class PublishLanguageFiles
 * @package Mcms\FrontEnd\Console\Commands\InstallerActions
 */
class PublishLanguageFiles
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['lang'],
        ]);
        $command->comment('Language files published');
    }
}