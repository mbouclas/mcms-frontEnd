<?php

namespace Mcms\FrontEnd\Models\Filters;


use App;


use Carbon\Carbon;
use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableExtraFields;
use Mcms\Core\QueryFilters\FilterableLimit;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;


class FormFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy, FilterableLimit, FilterableExtraFields;

    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'title',
        'slug',
        'dateStart',
        'dateEnd',
        'extraFields',
    ];

    /**
     * @example ?id=1,0
     * @param null|string $id
     * @return mixed
     */
    public function id($id = null)
    {
        if ( ! isset($id)){
            return $this->builder;
        }


        if (! is_array($id)) {
            $id = $id = explode(',',$id);
        }

        return $this->builder->whereIn('id', $id);
    }



    /**
     * @param null|string $title
     * @return $this
     */
    public function title($title = null)
    {
        if ( ! $title){
            return $this->builder;
        }

        return $this->builder->where("title", 'LIKE', "%{$title}%");
    }

    public function slug($slug = null)
    {
        if ( ! $slug){
            return $this->builder;
        }

        return $this->builder->where("slug", 'LIKE', "%{$slug}%");
    }


}