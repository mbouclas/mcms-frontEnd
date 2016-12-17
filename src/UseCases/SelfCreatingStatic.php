<?php

namespace Mcms\FrontEnd\UseCases;
/**
 * This is a use case pattern. What it does is, it provides a clear workflow for some very complex tasks
 * in the handle method. So, if someone wants an overview of what is going on in this part of the app, all
 * they have to do is checkout this class. Usually we name it something like PurchaseProduct or CompleteOrder.
 * ONLY USE THIS PATTERN FOR EXTRA COMPLICATE SCENARIOS!!!!
 */

/**
 * Extends the abstract class UseCase
 * Class SelfCreatingStatic
 * @package FrontEnd\UseCases
 */
class SelfCreatingStatic extends UseCaseAbstract
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