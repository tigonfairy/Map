<?php

namespace App\Models;

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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot('value');
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

}
