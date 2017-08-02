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
        ])->where('level',0);

        return Datatables::eloquent($model)
            ->editColumn('cbd', function ($model) {
                $product =Product::where('level',1)->where('name_code','cbd')->where('product_id',$model->id)->first();
                if($product) {
                    return $product->code;
                }
                return '';
            })
            ->editColumn('maxgreen', function ($model) {
                $product =Product::where('level',1)->where('name_code','maxgreen')->where('product_id',$model->id)->first();
                if($product) {
                    return $product->code;
                }
                return '';
            })->editColumn('maxgro', function ($model) {
                $product =Product::where('level',1)->where('name_code','maxgro')->where('product_id',$model->id)->first();
                if($product) {
                    return $product->code;
                }
                return '';
            })
            ->editColumn('group', function ($model) {
                $group = $model->group;
                if($group) {
                    return $group->name;
                }
                return '';
            })

            ->addColumn('action', 'admin.product.datatables.action')
            ->make(true);
    }
    public function group(){
        return $this->belongsTo(GroupProduct::class,'parent_id','id');
    }
    public function cbd() {
        $p = Product::where('level',1)->where('product_id',$this->getAttribute('id'))->where('name_code','cbd')->first();
        return $p;
    }

    public function maxgreen() {
        $p = Product::where('level',1)->where('product_id',$this->getAttribute('id'))->where('name_code','maxgreen')->first();
        return $p;
    }
    public function maxgro() {
        $p = Product::where('level',1)->where('product_id',$this->getAttribute('id'))->where('name_code','maxgro')->first();
        return $p;
    }
}
