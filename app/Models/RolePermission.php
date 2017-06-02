<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'permission_id','value'];
    /**
     * Get all of the tags for the post.
     */

    public static function boot()
    {
        parent::boot();

        static::created(function ($rolePermission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $rolePermission->id,
                'object_type' => 'RolePermission',
                'action'      => 'created',
                'data'      => json_encode($rolePermission),
            ]);
        });
        static::updated(function ($rolePermission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $rolePermission->id,
                'object_type' => 'RolePermission',
                'action'      => 'updated',
                'data'      => json_encode($rolePermission),
                'current_data'      => json_encode($rolePermission->getOriginal()),
            ]);
        });
        static::deleted(function ($rolePermission) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $rolePermission->id,
                'object_type' => 'RolePermission',
                'action'      => 'deleted',
                'current_data'      => json_encode($rolePermission->getOriginal()),
            ]);
        });
    }
}
