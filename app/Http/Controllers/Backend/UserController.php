<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
class UserController extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
        return view('admin.user.index');
    }

    public function add()
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }

        $roles = Role::all();
        $permission = Permission::all();
        $users = User::pluck('name', 'id')->all();

        return view('admin.user.form',compact('roles','permission', 'users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }

        $this->validate($request,[
            'name' =>'required',
            'code' =>'required',
            'position' =>'required',
            'email' =>'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'

        ]);
        $data = $request->all();
        if (!($password = $request->input('password'))) {
            $password = str_random(12);
        }

        $data['password'] = bcrypt($password);
        $user = User::create($data);
        $user->roles()->sync($request->input('role',[]));
//        $permissions = [];


//        foreach($request->input('status',[]) as $permissionId => $value) {
//            $permissions[$permissionId] = ['value' => $value];
//        }
//
//        $user->permissions()->sync($permissions);


        $user->save();
        return redirect()->route('Admin::user@index')
            ->with('success', 'Đã thêm Thanh vien');
    }

    public function edit($id)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->keyBy('id');
        $userPermissions = $user->permissions->keyBy('id');
        $permission = Permission::all();
        $users = User::pluck('name', 'id')->all();
        return view('admin.user.form', compact('user','roles', 'userRoles', 'permission', 'userPermissions', 'users'));
    }

    public function update($id, Request $request)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' =>'required',
            'code' =>'required',
            'position' =>'required'
        ]);

        $data = $request->all();
        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->roles()->sync($request->input('role',[]));
//        $permissions = [];
//
//
//        foreach($request->input('status',[]) as $permissionId => $value) {
//
//            $permissions[$permissionId] = ['value' => $value];
//
//        }
//
//        $user->permissions()->sync($permissions);


        $user->update($data);

        return redirect()->route('Admin::user@index', $user->id)
            ->with('success', 'Đã cập nhật thông tin User');

    }

    public function delete($id)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('Admin::user@index')->with('success', 'Đã xoá thành công');
    }

    public function getDatatables()
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
        return User::getDatatables();
    }
}
