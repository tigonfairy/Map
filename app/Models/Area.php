<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = [
        'name','manager_id','parent_id','border_color','background_color'
    ];

    public function address()
    {
        return $this->belongsToMany(AddressGeojson::class, 'area_address','area_id','address_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'manager_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($area) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $area->id,
                'object_type' => 'Area',
                'action'      => 'created',
                'data'      => json_encode($area),
            ]);
        });
        static::updated(function ($area) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $area->id,
                'object_type' => 'Area',
                'action'      => 'updated',
                'data'      => json_encode($area),
                'current_data'      => json_encode($area->getOriginal()),
            ]);
        });
        static::deleted(function ($area) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $area->id,
                'object_type' => 'Area',
                'action'      => 'deleted',
                'current_data'      => json_encode($area->getOriginal()),
            ]);
        });
    }

}
