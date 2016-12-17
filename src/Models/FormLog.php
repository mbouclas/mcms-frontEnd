<?php

namespace Mcms\FrontEnd\Models;

use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class FormLog extends Model
{
    use Filterable;

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['form_id', 'data'];

    public $casts = [
        'data' => 'array',

    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

    }

    public function form()
    {
        return $this->belongsTo(FormBuilder::class);
    }
}
