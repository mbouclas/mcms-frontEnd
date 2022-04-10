<?php

Route::group(['prefix'=> 'webhooks'], function($router) {
    $router->post('ssg/build/success' ,'Mcms\FrontEnd\Http\Controllers\SsgController@onBuildSuccess');
    $router->post('ssg/build/fail' ,'Mcms\FrontEnd\Http\Controllers\SsgController@onBuildFailed');
    $router->post('ssg/build/progress' ,'Mcms\FrontEnd\Http\Controllers\SsgController@onBuildProgress');
});

Route::group(['prefix' => 'admin/api'], function ($router) {
    $router->get('ssg/notifications/{id}' ,'Mcms\FrontEnd\Http\Controllers\SsgController@getDataStream')->name('__sse_stream__');

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
        $router->get('ssg/boot' ,'Mcms\FrontEnd\Http\Controllers\SsgController@boot');
        $router->get('ssg/deployment/{id}' ,'Mcms\FrontEnd\Http\Controllers\SsgController@getDeployment');
        $router->resource('ssg' ,'Mcms\FrontEnd\Http\Controllers\SsgController');
        $router->post('ssg/start-build' ,'Mcms\FrontEnd\Http\Controllers\SsgController@startBuild');



    });

});

//create the form builder route

Route::group(['prefix' => '/'], function ($router) {
    $formBuilderConfigurator = (Config::has('formBuilder.configurator'))
        ? Config::get('formBuilder.configurator')
        : \Mcms\FrontEnd\FormBuilder\BaseFormBuilderConfigurator::class;

    (new $formBuilderConfigurator())->createRoute($router);
});
