<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate\CreateMissingTable;

use Illuminate\Console\Command;
use Schema;

class CreateSsgHistoryTable
{
    public function handle(Command $command) {
        if ( ! Schema::hasTable('ssg_build_history')){
            $file = '2022_04_07_055407_create_ssg_build_history_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate', [
                '--path' => base_path() . '/database/migrations/' .$file
            ]);
        }
    }
}
