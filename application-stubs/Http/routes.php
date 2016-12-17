<?php
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localize', 'web' ] // Route translate middleware
],
    function()
    {
        Route::get('/', ['as' => 'home', 'uses' => 'FrontEnd\Http\Controllers\HomeController@index']);
        Route::get('login', 'Mcms\FrontEnd\Http\Controllers\Auth\AuthController@showLoginForm');
        Route::post('login', 'Mcms\FrontEnd\Http\Controllers\Auth\AuthController@login');
        Route::get('logout', 'Mcms\FrontEnd\Http\Controllers\Auth\AuthController@logout');
        Route::get('/contact', ['as' => 'contact', 'uses' => 'FrontEnd\Http\Controllers\ContactController@index']);
        Route::get('register', 'Mcms\FrontEnd\Http\Controllers\Auth\RegisterController@showRegistrationForm');
        Route::post('register', 'Mcms\FrontEnd\Http\Controllers\Auth\RegisterController@register');

        Route::get('/page/{id}/{slug}', ['as' => 'page', 'uses'=> 'FrontEnd\Http\Controllers\ArticleController@index']);
        Route::get('/pages/{slug}', ['as' => 'pages', 'uses'=> 'FrontEnd\Http\Controllers\ArticleController@articles']);
    });


Route::group(['middleware' =>[ 'web', 'auth']], function () {
    Route::get('/dashboard', ['as' => 'home', 'uses' => 'FrontEnd\Http\Controllers\HomeController@index']);
});

Route::get('register/verify/{confirmation_code}', [
    'uses' => 'Mcms\FrontEnd\Http\Controllers\Auth\RegisterController@verify',
    'as' => 'verify-user'
]);

Route::group(['middleware' => ['guest', 'web']], function ($router) {
    // Password Reset Routes...
    $router->get('password/reset', 'Mcms\FrontEnd\Http\Controllers\Auth\PasswordController@showLinkRequestForm');
    $router->post('password/email', 'Mcms\FrontEnd\Http\Controllers\Auth\PasswordController@sendResetLinkEmail');
    $router->get('password/reset/{token}', 'Mcms\FrontEnd\Http\Controllers\Auth\ResetPasswordController@showResetForm');
    $router->post('password/reset', 'Mcms\FrontEnd\Http\Controllers\Auth\ResetPasswordController@reset');
});

// Registration Routes...

Route::post('register', 'Mcms\FrontEnd\Http\Controllers\Auth\RegisterController@register');

Route::get('/sitemap.xml', ['as' => 'sitemap', 'uses'=> 'FrontEnd\Http\Controllers\SiteMapController@index']);