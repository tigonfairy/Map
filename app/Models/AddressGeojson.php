<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class AddressGeojson extends Model
{
    protected $table = 'address_geojson';
    protected $fillable = [
        'name','slug','coordinates'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($addressGeojson) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $addressGeojson->id,
                'object_type' => 'AddressGeojson',
                'action'      => 'created',
                'data'      => json_encode($addressGeojson),
            ]);
        });
        static::updated(function ($addressGeojson) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $addressGeojson->id,
                'object_type' => 'AddressGeojson',
                'action'      => 'updated',
                'data'      => json_encode($addressGeojson),
                'current_data'      => json_encode($addressGeojson->getOriginal()),
            ]);
        });
        static::deleted(function ($addressGeojson) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $addressGeojson->id,
                'object_type' => 'AddressGeojson',
                'action'      => 'deleted',
                'current_data'      => json_encode($addressGeojson->getOriginal()),
            ]);
        });
    }
}
