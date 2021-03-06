<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'title', 'content', 'date_time', 'unread',
        'user_id'
    ];
    protected $casts = [
        'content' => 'array',
    ];
}
