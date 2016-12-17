<?php

namespace FrontEnd\Http\Controllers;


use FrontEnd\Services\SiteMap;
use Illuminate\Routing\Controller as BaseController;


class SiteMapController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SiteMap $siteMap)
    {
        $map = $siteMap->getSiteMap();

        return response($map)
            ->header('Content-type', 'text/xml');
    }

}
