<?php

namespace App\Models;

use Datatables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getDatatables()
    {
        $model = static::select([
            '*'
        ])->with('users');

        return Datatables::eloquent($model)
            ->filter(function ($query) {
                if (request()->has('updated_at')) {
                    $date = request('updated_at');

                    $from = trim(explode(' - ', $date)[0]);
                    $from = Carbon::createFromFormat('d/m/Y', $from)->startOfDay()->toDateTimeString();

                    $to = trim(explode('-', $date)[1]);
                    $to = Carbon::createFromFormat('d/m/Y', $to)->endOfDay()->toDateTimeString();

                    $query->where('logs.updated_at', '>', $from);
                    $query->where('logs.updated_at', '<', $to);
                }
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at;
            })
            ->editColumn('user', function ($model) {
                return $model->users ? $model->users->email : '';
            })
            ->editColumn('action', function ($model) {
                return $model->action;
            })
            ->editColumn('content', function ($model) {
                return $model->object_type ? $model->object_type : '';
            })
            ->addColumn('detail', 'admin.history.datatables.action')
            ->make(true);
    }
}
