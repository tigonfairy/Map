<?php

namespace App\Models;

use App\Http\Requests\Request;
use Auth;
use Datatables;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name_vn','name_en','code','name_code','product_id','parent_id','level'
    ];
    public static function getDatatables(Request $request)
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
                   $raw_locale = \Session::get('locale');
                    if($raw_locale != null and $raw_locale == 'en') {
                        return ($group->name_en) ? $group->name_en : $group->name_vn;
                    }
                    return $group->name_vn;
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

    public static function getParent() {
        $ps = Product::where('level',0)->get();
        return $ps;
    }

    public function getChildren() {
       return $this->hasMany(Product::class, 'product_id','id');
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
