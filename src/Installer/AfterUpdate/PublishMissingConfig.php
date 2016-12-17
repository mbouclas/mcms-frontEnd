<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate;


use Mcms\Core\Models\UpdatesLog;
use Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingConfig\PublishFormBuilderConfig;
use Illuminate\Console\Command;

class PublishMissingConfig
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            PublishFormBuilderConfig::class
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }
        $item->result = true;
        $item->save();
        $command->comment('All done in PublishMissingConfig');
    }
}