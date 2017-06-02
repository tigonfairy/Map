<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_address';
    protected $fillable = [
        'user_id', 'place','border_color','background_color'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($userAddress) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userAddress->id,
                'object_type' => 'UserAddress',
                'action'      => 'created',
                'data'      => json_encode($userAddress),
            ]);
        });
        static::updated(function ($userAddress) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userAddress->id,
                'object_type' => 'UserAddress',
                'action'      => 'updated',
                'data'      => json_encode($userAddress),
                'current_data'      => json_encode($userAddress->getOriginal()),
            ]);
        });
        static::deleted(function ($userAddress) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $userAddress->id,
                'object_type' => 'UserAddress',
                'action'      => 'deleted',
                'current_data'      => json_encode($userAddress->getOriginal()),
            ]);
        });
    }
}
