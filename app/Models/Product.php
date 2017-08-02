<?php

namespace App\Models;

use Auth;
use Datatables;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static function getDatatables()
    {
        $model = static::select([
            '*'
        ])->where('level',1);

        return Datatables::eloquent($model)
            ->editColumn('code', function ($model) {
                return $model->name_code.':'.$model->code;
            })
            ->editColumn('name', function ($model) {
                return Auth::user()->lang == 'en' ? $model->name_vn : $model->name_en ;
            })
            ->addColumn('action', 'admin.product.datatables.action')
            ->make(true);
    }
}
