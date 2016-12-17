<?php

namespace Mcms\FrontEnd\Services;


use Exception;
use Mcms\FrontEnd\Models\PermalinkArchive as PermalinkArchiveModel;
use Illuminate\Http\Request;
use LaravelLocalization;

class PermalinkArchive
{
    protected $archive;
    /**
     * @var
     */
    private static $instance;

    public function __construct()
    {
        $this->init();
    }

    protected function init(){
        $this->archive = new PermalinkArchiveModel;
    }

    public function create($old, $new)
    {
        //we need old_link - new_link fields
        //when inserting we make sure they are of unique pair

        /*
         *    (A)         (B)
         * /page/as(1) -> /page/as1(2) <- in the next step, (2) needs to be updated to (4)
         * /page/as1(3) -> /page/as2(4) //invalid in the 3rd update as it no longer exists. We need to delete it
         * /page/as2(5) -> /page/as3(6)
         */

        //IF old one (4) exists as new one (5), delete the new version (4) and then insert (5)

        //make sure we start with /
        if (! ($old{0} == '/')) {
            $old = "/{$old}";
        }

        if (! ($new{0} == '/')) {
            $new = "/{$new}";
        }

        $existingCount = $this->archive->
        where('new_link', $new)
            ->where('old_link', $old)
            ->count();

        if ($existingCount > 0) {
            return $this;
        }

        $found = $this->archive->where('new_link', $old)->first();
        if ($found) {
            $found->new_link = $new;
            $found->save();//this now points to the correct one
        }

        //add the new
        $this->archive->create([
            'old_link' => $old,
            'new_link' => $new
        ]);

        return $this;
    }

    public static function add($old, $new)
    {
        self::instance()->create($old, $new);
        return self::$instance;
    }

    public function find(Request $request, Exception $e)
    {
        $path = '/' . $request->path();
        $search = [$path, $path."/"];
        //create combinations for the lang
        $locales = LaravelLocalization::getSupportedLocales();
        foreach ($locales as $locale){
            $search[] = "/{$locale['code']}{$path}";
        }

        $found = $this->archive->whereIn('old_link',$search)->first();
        return ( $found ) ? $found->new_link : null;
    }

    public static function lookUp(Request $request, Exception $e)
    {
        return self::instance()->find($request, $e);
    }

    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}