<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permissions')->withPivot('value');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($role) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $role->id,
                'object_type' => 'Role',
                'action'      => 'created',
                'data'      => json_encode($role),
            ]);
        });
        static::updated(function ($role) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $role->id,
                'object_type' => 'Role',
                'action'      => 'updated',
                'data'      => json_encode($role),
                'current_data'      => json_encode($role->getOriginal()),
            ]);
        });
        static::deleted(function ($role) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $role->id,
                'object_type' => 'Role',
                'action'      => 'deleted',
                'current_data'      => json_encode($role->getOriginal()),
            ]);
        });
    }
}
