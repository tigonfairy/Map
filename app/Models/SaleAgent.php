<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleAgent extends Model
{
    protected $table = 'sale_agents';
    protected $fillable = ['id', 'agent_id', 'product_id', 'month', 'sales_plan', 'sales_real'];
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
