<?php

namespace Mcms\FrontEnd\UseCases;

use App\Jobs\Job;

/**
 * Laravel based use case
 * Called in the Controller as dispatch(new PerformAComplexJob('any arguments'));
 * Class PerformAComplexJob
 * @package FrontEnd\UseCases
 */
class PerformAComplexJob extends Job
{

    public function handle()
    {
        $this->sendEmail()
            ->triggerEvent()
            ->doSomethingElse();
    }

    private function sendEmail()
    {
        return $this;
    }

    private function triggerEvent()
    {
        return $this;
    }

    private function doSomethingElse()
    {
        return $this;
    }
}
