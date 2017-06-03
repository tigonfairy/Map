<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
class Agent extends Model
{
    protected $table = 'agents';
    protected $fillable = [
        'name','manager_id','lat','lng'
    ];

    public function user(){
        return $this->belongsTo(User::class,'manager_id','id');
    }
    public static function boot()
    {
        parent::boot();

        static::created(function ($agent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'created',
                'data'      => json_encode($agent),
            ]);
        });
        static::updated(function ($agent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'updated',
                'data'      => json_encode($agent),
                'current_data'      => json_encode($agent->getOriginal()),
            ]);
        });
        static::deleted(function ($agent) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'deleted',
                'current_data'      => json_encode($agent->getOriginal()),
            ]);
        });
    }

}
