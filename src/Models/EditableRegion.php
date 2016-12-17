<?php

namespace Mcms\FrontEnd\Models;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Multilingual\Translatable;


/**
 * Class EditableRegion
 * @package Mcms\FrontEnd\Models
 */
class EditableRegion extends Model
{
    use Translatable;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['layout', 'region', 'items', 'settings'];

    public $casts = [
        'items' => 'array',
        'settings' => 'array',
    ];

    public $translatable = ['items'];

}
