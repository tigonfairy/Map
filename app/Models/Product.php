<?php

namespace App\Models;

use Datatables;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'name'];

    public static function getDatatables()
    {
        $model = static::select([
            'id', 'name', 'created_at'
        ]);

        return Datatables::eloquent($model)
            ->filter(function ($query) {
            })
            ->addColumn('action', 'admin.product.datatables.action')
            ->make(true);
    }
}
