<?php

namespace App\Models;

use Auth;
use Datatables;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Agent;
use App\Models\Area;
use Cache;
class User extends Authenticatable
{
//    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const NVKD = 1;
    const GSV = 2;
    const TV = 3;
    const GĐV = 4;
    const SALE_ADMIN = 5;
    const ADMIN = 6;

    public static $positionTexts = [
        self::NVKD => 'Nhân viên kinh doanh',
        self::GSV => 'Giám sát vùng',
        self::TV =>  ' Trưởng vùng',
        self::GĐV =>  'Giám đốc vùng',
        self::SALE_ADMIN =>  ' Phó GĐ kinh doanh',
        self::ADMIN =>  'Admin',



    ];


    protected $fillable = [
        'email', 'permission_id', 'remember_token', 'password', 'code', 'name', 'manager_id', 'position',
        'lang', 'status','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function area(){
        return $this->hasMany(Area::class,'manager_id','id');
    }
    public function agent(){
        return $this->hasMany(Agent::class,'manager_id','id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot('value');
    }

    public function manager()
    {
        return $this->hasMany(User::class, 'manager_id');
    }


    public static function getDatatables()
    {
        $user = auth()->user();
        $role = $user->roles()->first();
        if($role->id == 1) {
            $model = static::select([
                'id', 'email', 'created_at'
            ])->with('roles');
        } else {
            $users = $user->manager()->get();
            $users->push($user);
            $userIds = $users->pluck('id')->toArray();
            foreach ($users as $key => $value) {
                $userId = $value->manager()->get()->pluck('id')->toArray();
                foreach ($userId as $k => $v) {
                    $userIds[] = $v;
                }
            }
            $model = static::select([
                'id', 'email', 'created_at'
            ])->with('roles')->whereIn('id', $userIds);
        }


        return Datatables::eloquent($model)
            ->filter(function ($query) {
            })
            ->editColumn('group', function ($model) {
                return $model->roles ? $model->roles->first()['name'] : '';
            })
            ->addColumn('action', 'admin.user.datatables.action')
            ->make(true);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'created',
                'data'      => json_encode($user),
            ]);
        });
        static::updated(function ($user) {

            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'updated',
                'data'      => json_encode($user),
                'current_data'      => json_encode($user->getOriginal()),
            ]);
        });
        static::deleted(function ($user) {
            return Log::forceCreate([
                'user_id'     => Auth::user()->id ? Auth::user()->id : 0,
                'object_id'   => $user->id,
                'object_type' => 'User',
                'action'      => 'deleted',
                'current_data'      => json_encode($user->getOriginal()),
            ]);
        });
    }

    public static function getListAgencyByRole(){
        $user = auth()->user();
        $role = $user->roles()->first();
//        if($role->id != 2 ){
//            return null;
//        }
        $area = $user->area()->get()->pluck('id')->toArray();
        $subArea = Area::whereIn('parent_id',$area)->get()->pluck('id')->toArray();
        $areaIds = array_unique(array_merge($area,$subArea));
        $agents = Agent::whereIn('area_id',$areaIds)->get()->pluck('id')->toArray();
        //agent Id of user
        $agentId = $user->agent()->get()->pluck('id')->toArray();
        $agentId= array_unique(array_merge($agentId,$agents));

        return $agentId;
    }

}
