<?php

namespace Mcms\FrontEnd\StartUp;

use Mcms\FrontEnd\FormBuilder\FormsInterfaceMenuConnector;
use Illuminate\Support\ServiceProvider;
use ItemConnector;
use ModuleRegistry;

class RegisterAdminPackage
{
    public function handle(ServiceProvider $serviceProvider)
    {
        ModuleRegistry::registerModule($serviceProvider->packageName . '/admin.package.json');
        try {
            ItemConnector::register((new FormsInterfaceMenuConnector())->run()->toArray());
        } catch (\Exception $e){

        }
    }
}