<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Mcms\FrontEnd\Console\Commands\InstallerActions\Seeders\FormBuilder;
use Illuminate\Console\Command;

class SeedDataBase
{
    public function handle(Command $command)
    {
        (new FormBuilder())->handle();
        $command->comment('Database seeded');
    }
}