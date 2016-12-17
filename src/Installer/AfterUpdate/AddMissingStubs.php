<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate;

use File;
use Mcms\Core\Models\UpdatesLog;
use Illuminate\Console\Command;

class AddMissingStubs
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [

        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }
        $item->result = true;
        $item->save();
        $command->comment('All done in AddMissingStubs');
    }
}