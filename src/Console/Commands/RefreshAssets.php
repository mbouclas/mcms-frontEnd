<?php

namespace Mcms\FrontEnd\Console\Commands;

use File;
use Mcms\FrontEnd\Console\Commands\InstallerActions\PublishAssets;
use Mcms\FrontEnd\Installer\ActionsAfterUpdate;
use Illuminate\Console\Command;
class RefreshAssets extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frontend:refreshAssets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all old assets and copies the new ones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //remove the package directory
        $this->info('Removing old assets');
        File::deleteDirectory(public_path('front-end'));
        //bring the new assets into play
        $this->comment('done');
        $this->info('copying new assets');
        (new PublishAssets())->handle($this);
        (new ActionsAfterUpdate())->handle($this);

        return true;
    }
}
