<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Datatables;
use Auth;

class GroupProduct extends Model
{
    protected $fillable = ['name_vn','name_en','code'];
    public static function getDatatables()
    {
        $model = static::select([
            '*'
        ]);

        return Datatables::eloquent($model)
            ->addColumn('action', 'admin.group_products.datatables.action')
            ->make(true);
    }
    public function product() {
        return $this->hasMany(Product::class,'parent_id','id');
    }
    public function getNameAttribute() {
        $raw_locale = \Session::get('locale');
        if($raw_locale != null and $raw_locale == 'en') {
            if($this->attributes['name_en']) {
                return $this->attributes['name_en'];
            }
        }
        return $this->attributes['name_vn'];
    }
}
