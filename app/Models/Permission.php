<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'permissions';
    protected $fillable = [
        'id', 'description',
    ];
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        static::created(function ($permission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $permission->id,
                'object_type' => 'Permission',
                'action'      => 'created',
                'data'      => json_encode($permission),
            ]);
        });
        static::updated(function ($permission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $permission->id,
                'object_type' => 'Permission',
                'action'      => 'updated',
                'data'      => json_encode($permission),
                'current_data'      => json_encode($permission->getOriginal()),
            ]);
        });
        static::deleted(function ($permission) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $permission->id,
                'object_type' => 'Permission',
                'action'      => 'deleted',
                'current_data'      => json_encode($permission->getOriginal()),
            ]);
        });
    }
}
