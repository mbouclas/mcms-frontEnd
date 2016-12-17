<?php

namespace Mcms\FrontEnd\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;


class HomeController extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function preview()
    {
        return view('home');
    }
}
