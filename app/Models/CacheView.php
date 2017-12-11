<?php

namespace App\Models;

use Datatables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CacheView extends Model
{
    protected $table = 'cache_view';
    protected $fillable = [
      'agent_id'
    ];
}
