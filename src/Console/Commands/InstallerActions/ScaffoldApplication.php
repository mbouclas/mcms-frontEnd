<?php

namespace Mcms\FrontEnd\Console\Commands\InstallerActions;


use Mcms\Core\Helpers\Composer;
use Mcms\Core\Helpers\ConfigFiles;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ScaffoldApplication
 * @package Mcms\FrontEnd\Console\Commands\InstallerActions
 */
class ScaffoldApplication
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $email = $command->ask('What should the site email be?');
        $name = $command->ask('And the name for this address?');
        $config = new ConfigFiles('mail', true);
        $config->addChange('from', ['name' => $name, 'address'=> $email]);
/*        $config->contents['from']['address'] = $email;
        $config->contents['from']['name'] = $name;*/
        $config->save();

        $command->call('vendor:publish', [
            '--provider' => 'Mcms\FrontEnd\FrontEndServiceProvider',
            '--tag' => ['scaffold'],
        ]);
        $config = new ConfigFiles('app', true);
        $config->addToArray('providers', 'Illuminate\Notifications\NotificationServiceProvider::class', true)->save();
        $config = new ConfigFiles('app', true);
        $config->addToArray('providers', '\FrontEnd\CustomServiceProvider::class', true)->save();
        $config = new ConfigFiles('app', true);
        $config->addToArray('providers', '\FrontEnd\Providers\ViewComposerServiceProvider::class', true)->save();
/*        $config->contents['providers'][] = \FrontEnd\CustomServiceProvider::class;
        $config->contents['providers'][] = \FrontEnd\Providers\ViewComposerServiceProvider::class;
        $config->save();*/
/*
        $appConfigLine = 'Mcms\Core\CoreServiceProvider::class,

        FrontEnd\\CustomServiceProvider::class,';//app.php lookup line*/
/*        $requirement = '"psr-4": {
            "FrontEnd\\\\": "FrontEnd/",';//composer lookup line*/

        //Add it to the service providers
/*        $fs->replaceAndSave(base_path('config/app.php'),
            'Mcms\Core\CoreServiceProvider::class,
            FrontEnd\Providers\ViewComposerServiceProvider::class,',
            $appConfigLine);*/

        //Add it to composer.json
        $composer = new Composer();
        if ( ! isset($composer->contents['autoload']['psr-4'])){
            $composer->contents['autoload']['psr-4'] = [];
        }

        $composer->contents['autoload']['psr-4']["FrontEnd\\"] = "FrontEnd/";
        $composer->save();
//        $fs->replaceAndSave(base_path('composer.json'), '"psr-4": {', $requirement);

        //add the 301 Permalink handler
        $handlerLookUp = 'return parent::render($request, $exception);';
        $fs = new \Mcms\Core\Helpers\FileSystem(new Filesystem());
        $fs->replaceAndSave(app_path('Exceptions/Handler.php'),
            $handlerLookUp,
            '
            /*
             * Added by the FrontEnd package
             */
            if($this->isHttpException($exception)){
                $redirectTo = \Mcms\FrontEnd\Services\PermalinkArchive::lookUp($request, $exception);
                if ($redirectTo){
                    return \Redirect::to($redirectTo, 301);
                }
             }
             ' . $handlerLookUp);
        $debugBarLookUp = 'APP_DEBUG=true';
        $debugBarReplace = 'APP_DEBUG=true
                            APP_DEBUGBAR=true';

        $fs->replaceAndSave(base_path('.env'), $debugBarLookUp, $debugBarReplace);
        // run artisan optimize
        $command->call('optimize', []);

        //Run composer dump-autoload
        $command->comment('Application scaffold complete');
    }
}