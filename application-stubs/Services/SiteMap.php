<?php

namespace FrontEnd\Services;
use Carbon\Carbon;
use Mcms\Pages\Models\Page;
use Illuminate\Support\Facades\Cache;

class SiteMap
{
    /**
     * Return the content of the Site Map
     */
    public function getSiteMap()
    {
        if (Cache::has('site-map')) {
//            return Cache::get('site-map');
        }

        $siteMap = $this->buildSiteMap();
//        Cache::add('site-map', $siteMap, 120);
        return $siteMap;
    }

    /**
     * Build the Site Map
     */
    protected function buildSiteMap()
    {
        $postsInfo = $this->getPostsInfo();
        foreach ($postsInfo as $post){
            $dates[] = $post->updated_at;
        }
        sort($dates);
        $lastmod = last($dates);
        $lastmod = $lastmod->format('Y-m-d\TH:i:sP');
        $url = str_finish(url('/'), '/');

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?'.'>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml[] = '  <url>';
        $xml[] = "    <loc>$url</loc>";
        $xml[] = "    <lastmod>$lastmod</lastmod>";
        $xml[] = '    <changefreq>daily</changefreq>';
        $xml[] = '    <priority>0.8</priority>';
        $xml[] = '  </url>';

        foreach ($postsInfo as $item) {

            $itemUrl = url($item->generateSlug());
            $xml[] = '  <url>';
            $xml[] = "    <loc>{$itemUrl}</loc>";
            $xml[] = "    <lastmod>{$item->updated_at->format('Y-m-d\TH:i:sP')}</lastmod>";
            $xml[] = "  </url>";
        }

        $xml[] = '</urlset>';

        return join("\n", $xml);
    }

    /**
     * Return all the posts as $url => $date
     */
    protected function getPostsInfo()
    {
        return Page::where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}