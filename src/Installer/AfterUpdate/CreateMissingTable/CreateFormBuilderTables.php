<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate\CreateMissingTable;

use Illuminate\Console\Command;
use Schema;

class CreateFormBuilderTables
{
    public function handle(Command $command)
    {

        if ( ! Schema::hasTable('form_builders')){
            $file = '2016_10_24_172025_create_form_builders_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }

        if ( ! Schema::hasTable('form_logs')){
            $file = '2016_10_28_123533_create_form_logs_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }
    }
}