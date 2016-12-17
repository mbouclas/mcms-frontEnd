<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate;

use Mcms\Core\Models\UpdatesLog;
use Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingViews\FormsWidgetViews;
use Illuminate\Console\Command;

class PublishMissingViews
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            FormsWidgetViews::class
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }
        $item->result = true;
        $item->save();
        $command->comment('All views done');
    }
}