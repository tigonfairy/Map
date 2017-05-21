<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);


        $gate->before(function ($user, $ability) {

            return $this->can($user, $ability);
        });


    }

    private function can($user, $permission)
    {

        if($user->id==1){
            return true;
        }
        $permissions = $user->permissions->keyBy('id');
        if (isset($permissions[$permission])) {
            if ($permissions[$permission]->pivot->value == 0) {
                return true;
            } else if ($permissions[$permission]->pivot->value == 1) {
                return false;
            }
        }

        $roles = $user->roles;

        foreach ($roles as $role) {
            $permissions = $role->permissions->keyBy('id');
            if (isset($permissions[$permission])) {
                if ($permissions[$permission]->pivot->value == 0) {
                    return true;
                } else if ($permissions[$permission]->pivot->value == 1) {
                    return false;
                }
            }
        }
        return false;
    }
}
