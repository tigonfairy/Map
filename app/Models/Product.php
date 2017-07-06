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
        ]);

        return Datatables::eloquent($model)
            ->editColumn('name', function ($model) {
                return Auth::user()->lang == 'en' ? $model->nameEng : $model->name ;
            })
            ->addColumn('action', 'admin.product.datatables.action')
            ->make(true);
    }
}
