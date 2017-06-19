<?php

namespace App\Models;

use Auth;
use Datatables;
use Illuminate\Database\Eloquent\Model;

class SaleAgent extends Model
{
    protected $table = 'sale_agents';
    protected $fillable = ['id', 'agent_id', 'product_id', 'month', 'sales_plan', 'sales_real'];


    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');

    }
    public function agent() {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function products() {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public static function getDatatables()
    {
        $user = auth()->user();
        $role = $user->roles()->first();
        if($role->id == 1) {
            $model = static::groupBy('agent_id', 'month')->select([
                '*'
            ])->with('agent');
        } else {
            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $managerIds = $userOwns->pluck('id')->toArray();

            $model = static::whereHas('agent', function ($query) use ($managerIds){
                $query->whereIn('agents.manager_id', $managerIds);
            })->groupBy('agent_id', 'month')->select([
                '*'
            ])->with('agent');
        }


        return Datatables::eloquent($model)
            ->filter(function ($query) {
                if (request()->has('agent')) {
                    $query->whereHas('agent', function($query)
                    {
                        $query->where('name', 'like' , '%'.request('agent').'%' );
                    });
                }

                if (request()->has('month')) {
                    $query->where('month', request('month') );
                }

            })
            ->editColumn('agent', function ($model) {
                return $model->agent ? $model->agent->name : '';
            })
            ->addColumn('action', 'admin.saleAgent.datatables.action')
            ->make(true);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($saleAgent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $saleAgent->id,
                'object_type' => 'Sale Agent',
                'action'      => 'created',
                'data'      => json_encode($saleAgent),
            ]);
        });
        static::updated(function ($saleAgent) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $saleAgent->id,
                'object_type' => 'Sale Agent',
                'action'      => 'updated',
                'data'      => json_encode($saleAgent),
                'current_data'      => json_encode($saleAgent->getOriginal()),
            ]);
        });
        static::deleted(function ($saleAgent) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $saleAgent->id,
                'object_type' => 'Sale Agent',
                'action'      => 'deleted',
                'current_data'      => json_encode($saleAgent->getOriginal()),
            ]);
        });

    }
}
