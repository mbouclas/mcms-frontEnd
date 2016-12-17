<?php

namespace FrontEnd\Http\Controllers;

use App\Http\Requests;
use Mcms\FrontEnd\Http\Controllers\HomeController as BaseHomeController;
use Illuminate\Http\Request;

class HomeController extends BaseHomeController
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
