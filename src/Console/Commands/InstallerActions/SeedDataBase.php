<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Mcms\FrontEnd\Console\Commands\InstallerActions\Seeders\FormBuilderSeed;
use Illuminate\Console\Command;

class SeedDataBase
{
    public function handle(Command $command)
    {
        (new FormBuilderSeed())->handle();
        $command->comment('Database seeded');
    }
}