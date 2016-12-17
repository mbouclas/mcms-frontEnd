<?php

namespace Mcms\FrontEnd\Console\Commands;

use Mcms\FrontEnd\Services\ImageOptimizer;
use Illuminate\Console\Command;

class OptimizeImages extends Command
{
    /**
     * @var array
     */
    protected $actions = [

    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {file} {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize images';

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
        ImageOptimizer::optimize($this->argument('file'));

        return true;
    }


}
