<?php

namespace Mcms\FrontEnd\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class PermalinkArchive
 * @package Mcms\FrontEnd\Models
 */
class PermalinkArchive extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = ['old_link', 'new_link'];

}
