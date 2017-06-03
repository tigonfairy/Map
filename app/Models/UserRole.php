<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'role_id'];
    /**
     * Get all of the tags for the post.
     */

    public static function boot()
    {
        parent::boot();

        static::created(function ($userRole) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userRole->id,
                'object_type' => 'UserRole',
                'action'      => 'created',
                'data'      => json_encode($userRole),
            ]);
        });
        static::updated(function ($userRole) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userRole->id,
                'object_type' => 'UserRole',
                'action'      => 'updated',
                'data'      => json_encode($userRole),
                'current_data'      => json_encode($userRole->getOriginal()),
            ]);
        });
        static::deleted(function ($userRole) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userRole->id,
                'object_type' => 'UserRole',
                'action'      => 'deleted',
                'current_data'      => json_encode($userRole->getOriginal()),
            ]);
        });
    }
}
