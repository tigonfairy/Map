<?php

namespace App\Models;

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
}
