<?php

namespace App\Models;

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
}
