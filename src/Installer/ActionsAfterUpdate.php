<?php

namespace Mcms\FrontEnd\Installer;


use Mcms\FrontEnd\Installer\AfterUpdate\AddMissingStubs;
use Mcms\FrontEnd\Installer\AfterUpdate\CreateMissingTable;
use Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingConfig;
use Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingMigrations;
use Mcms\Core\Exceptions\ErrorDuringUpdateException;
use Mcms\Core\Helpers\Installer;
use Mcms\Core\UpdatesLog\UpdatesLog;
use Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingViews;
use Mcms\FrontEnd\Installer\AfterUpdate\SeedTable;
use Illuminate\Console\Command;

class ActionsAfterUpdate
{
    protected $module;
    protected $version;

    public function __construct()
    {
        $this->module = 'front-end';
        $this->version = 3;
    }

    public function handle(Command $command)
    {
        /*
         * publish the missing migrations
         * publish the missing config
         * create the missing table media_library
         */


        $actions = [
            'AddMissingStubs' => AddMissingStubs::class,
            'PublishMissingMigrations' => PublishMissingMigrations::class,
            'PublishMissingConfig' => PublishMissingConfig::class,
            'CreateMissingTable' => CreateMissingTable::class,
            'PublishMissingViews' => PublishMissingViews::class,
            'SeedTable' => SeedTable::class,
        ];

        try {
            (new UpdatesLog($command, $this->module, $actions, $this->version))->process();
        }
        catch (ErrorDuringUpdateException $e){
            $command->error('Error during updating ' . $this->module);
        }

        return true;
    }
}