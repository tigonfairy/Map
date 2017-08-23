<?php

namespace App\Models;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
class Agent extends Model
{


    const diamond = 1;
    const gold = 2;
    const silver = 3;
    const unclassified = 4;
    const rival = 5;


    public static $statusRank = [
        self::diamond => 'Kim cương',
        self::gold => 'Vàng',
        self::silver => "Bạc",
        self::unclassified => 'Chưa phân hạng',
        self::rival => 'Đối thủ'
        ];
    public static $rankText = [
        self::diamond => 'Kim cương',
        self::gold => 'Vàng',
        self::silver => "Bạc",
        self::unclassified => 'Chưa phân hạng',
    ];
    const agentNew = 1;
    const agentRival = 2;

    protected $table = 'agents';
    protected $fillable = [
        'name', 'manager_id', 'lat', 'lng', 'area_id','icon','address','code','rank','attribute',
        'gdv','pgdkd','tv','gsv'
    ];

    public function user(){
        return $this->belongsTo(User::class,'manager_id','id');
    }

    public function product(){
        return $this->hasMany(SaleAgent::class,'agent_id','id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public static function getDataSales($listAgentIds, $month){
        return json_encode(SaleAgent::select('products.name as product_name', DB::raw('SUM(sales_real) as total_sales_real, SUM(sales_plan) as total_sales_plan'))
            ->leftjoin('products', 'sale_agents.product_id', '=', 'products.id')
            ->whereIn('agent_id', $listAgentIds)->where('month', $month)
            ->groupBy('product_id')->get());
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($agent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'created',
                'data'      => json_encode($agent),
            ]);
        });
        static::updated(function ($agent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'updated',
                'data'      => json_encode($agent),
                'current_data'      => json_encode($agent->getOriginal()),
            ]);
        });
        static::deleted(function ($agent) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $agent->id,
                'object_type' => 'Area',
                'action'      => 'deleted',
                'current_data'      => json_encode($agent->getOriginal()),
            ]);
        });
    }

}
