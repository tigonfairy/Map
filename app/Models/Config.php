<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'configs';
    protected $fillable = [
        'title', 'content', 'date_time', 'unread'
    ];
    protected $casts = [
        'content' => 'array',
    ];
}
