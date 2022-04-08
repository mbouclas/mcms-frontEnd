<?php

namespace Mcms\FrontEnd\Models;

use Illuminate\Database\Eloquent\Model;

class SsgBuildHistoryModel extends Model
{
    protected $table = 'ssg_build_history';
    protected $dates = ['created_at', 'updated_at', 'run_at'];
    protected $fillable = [
        'provider',
        'status',
        'token',
        'run_at',
        'user_id',
    ];
}
