<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate;


use Mcms\Core\Models\UpdatesLog;
use Mcms\FrontEnd\Installer\AfterUpdate\CreateMissingTable\CreateFormBuilderTables;
use Illuminate\Console\Command;

class CreateMissingTable
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            CreateFormBuilderTables::class
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }


        $item->result = true;
        $item->save();
        $command->comment('All done in CreateMissingTable');
    }
}