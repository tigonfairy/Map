<?php

namespace App\Models;

use Auth;
use Datatables;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
//    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'permission_id', 'remember_token', 'password', 'code', 'name', 'manager_id', 'position'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function area(){
        return $this->hasMany(Area::class,'manager_id','id');
    }
    public function agent(){
        return $this->hasMany(Agent::class,'manager_id','id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot('value');
    }

    public function manager()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public static function getDatatables()
    {
        $model = static::select([
            'id', 'email', 'created_at'
        ])->with('roles');

        return Datatables::eloquent($model)
            ->filter(function ($query) {
            })
            ->editColumn('group', function ($model) {
                return $model->roles ? $model->roles->first()['name'] : '';
            })
            ->addColumn('action', 'admin.user.datatables.action')
            ->make(true);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'created',
                'data'      => json_encode($user),
            ]);
        });
        static::updated(function ($user) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'updated',
                'data'      => json_encode($user),
                'current_data'      => json_encode($user->getOriginal()),
            ]);
        });
        static::deleted(function ($user) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'deleted',
                'current_data'      => json_encode($user->getOriginal()),
            ]);
        });
    }

}
