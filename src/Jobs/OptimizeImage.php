<?php

namespace Mcms\FrontEnd\Jobs;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OptimizeImage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        \Artisan::call('images:optimize', ['file' => $this->file]);
    }
}
