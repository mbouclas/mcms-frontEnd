<?php

namespace Mcms\FrontEnd\Services;

use Mcms\FrontEnd\Jobs\OptimizeImage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use ImageOptimizer\OptimizerFactory;

class ImageOptimizer
{
    use DispatchesJobs;
    protected $optimizer;
    protected $command;
    /**
     * @var
     */
    private static $instance;


    public static function optimize($file, $delayed = false){
        if ($delayed){
            //save for later
            $job = (new OptimizeImage($file))
                ->onQueue('imageOptimizer');

                self::instance()
                ->dispatch($job);
            return;
        }

        $factory = new OptimizerFactory();
        $optimizer = $factory->get();
        $optimizer->optimize($file);
    }

    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}