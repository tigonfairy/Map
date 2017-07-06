<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Datatables;
use Auth;

class GroupProduct extends Model
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
            ->addColumn('action', 'admin.group_products.datatables.action')
            ->make(true);
    }
}
