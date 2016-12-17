<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate;

use Mcms\Core\Models\UpdatesLog;
use Mcms\FrontEnd\Installer\AfterUpdate\SeedTable\SeedFormBuilder;
use Illuminate\Console\Command;

class SeedTable
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            SeedFormBuilder::class
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }
        $item->result = true;
        $item->save();
        $command->comment('All seeds done');
    }
}