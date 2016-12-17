<?php

namespace Mcms\FrontEnd\Http\Controllers\Admin;


use Illuminate\Routing\Controller;

class WelcomeWidgetController extends Controller
{
    public function index()
    {
        return \Config::get('frontEnd.welcomeWidget');
    }
}