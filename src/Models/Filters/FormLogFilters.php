<?php

namespace Mcms\FrontEnd\Models\Filters;


use App;


use Carbon\Carbon;
use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableExtraFields;
use Mcms\Core\QueryFilters\FilterableLimit;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;


class FormLogFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy, FilterableLimit, FilterableExtraFields;

    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'data',
        'form_id',
        'dateStart',
        'dateEnd',
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


    public function form_id($form_id = null)
    {
        if ( ! isset($form_id)){
            return $this->builder;
        }

        if (! is_array($form_id)) {
            $form_id = $form_id = explode(',',$form_id);
        }

        return $this->builder->whereIn('form_id', $form_id);
    }

    public function data($field = null)
    {
        if ( ! $field){
            return $this->builder;
        }

        $data = explode('::', $field);
        if (count($data) !== 2){
            return $this->builder;
        }

        return $this->builder->where("data->{$data[0]}", 'LIKE', "%{$data[1]}%");

    }
}