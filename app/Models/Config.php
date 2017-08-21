<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'configs';
    protected $fillable = [
        'fontSize', 'textColor', 'background', 'position_id'
    ];
//    protected $casts = [
//        'content' => 'array',
//    ];
}
