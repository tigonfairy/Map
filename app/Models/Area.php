<?php

namespace App\Models;

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

}
