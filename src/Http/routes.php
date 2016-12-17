<?php
Route::group(['prefix' => 'admin/api'], function () {
    Route::group(['middleware' =>['level:3']], function($router)
    {
        $router->get('editableRegions' ,'Mcms\FrontEnd\Http\Controllers\EditableRegionsController@index');
        $router->put('editableRegions/{id}' ,'Mcms\FrontEnd\Http\Controllers\EditableRegionsController@update');
        $router->resource('permalinkArchive' ,'Mcms\FrontEnd\Http\Controllers\PermalinkArchiveController');
        $router->resource('welcomeWidget' ,'Mcms\FrontEnd\Http\Controllers\Admin\WelcomeWidgetController');
        $router->get('formBuilder/template' ,'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController@template');
        $router->get('formBuilder/schema' ,'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController@schema');
        $router->get('formBuilder/providers' ,'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController@providers');
        $router->resource('formBuilder' ,'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController');
        $router->resource('formLog' ,'Mcms\FrontEnd\Http\Controllers\Admin\FormLogController');
    });

});

//create the form builder route

Route::group(['prefix' => '/'], function ($router) {
    $formBuilderConfigurator = (Config::has('formBuilder.configurator'))
        ? Config::get('formBuilder.configurator')
        : \Mcms\FrontEnd\FormBuilder\BaseFormBuilderConfigurator::class;

    (new $formBuilderConfigurator())->createRoute($router);
});
