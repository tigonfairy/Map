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
}
