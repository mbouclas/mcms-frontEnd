<?php

namespace Mcms\FrontEnd\Installer\AfterUpdate\PublishMissingViews;


use Illuminate\Console\Command;

class FormsWidgetViews
{
    public function handle(Command $command)
    {
        $file = 'widget.blade.php';

        $targetFile = base_path("resources/views/vendor/frontEnd/forms/$file");
        if ( ! \File::exists($targetFile)){
            \File::copyDirectory(__DIR__ . "/../../../../resources/views/vendor", base_path('resources/views/vendor'));
        }
    }
}