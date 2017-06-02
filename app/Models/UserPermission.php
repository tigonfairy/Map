<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    //
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'permission_id','value'];

    /**
     * Get all of the tags for the post.
     */

    public static function boot()
    {
        parent::boot();

        static::created(function ($userPermission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userPermission->id,
                'object_type' => 'UserPermission',
                'action'      => 'created',
                'data'      => json_encode($userPermission),
            ]);
        });
        static::updated(function ($userPermission) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userPermission->id,
                'object_type' => 'UserPermission',
                'action'      => 'updated',
                'data'      => json_encode($userPermission),
                'current_data'      => json_encode($userPermission->getOriginal()),
            ]);
        });
        static::deleted(function ($userPermission) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userPermission->id,
                'object_type' => 'UserPermission',
                'action'      => 'deleted',
                'current_data'      => json_encode($userPermission->getOriginal()),
            ]);
        });
    }
}
