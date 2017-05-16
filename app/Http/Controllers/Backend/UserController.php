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

        if (auth()->user()->cannot('list-user')) {
            abort(403);
        }
        $users = User::orderBy('created_at');
        if($request->input('search')){
            $users = $users->where('email','like','%'.$request->input('search').'%');
        }
        $users = $users->paginate(8);
        return view('admin.user.index',compact('users'));
    }

    public function add()
    {

        if (auth()->user()->cannot('add-user')) {
            abort(403);
        }

        $roles = Role::all();
        $permission = Permission::all();
        return view('admin.user.form',compact('roles','permission'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cannot('add-user')) {
            abort(403);
        }

        $this->validate($request,[
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
        $permissions = [];


        foreach($request->input('status',[]) as $permissionId => $value) {

            $permissions[$permissionId] = ['value' => $value];

        }

        $user->permissions()->sync($permissions);


        $user->save();
        return redirect()->route('user@index')
            ->with('success', 'Đã thêm Thanh vien');
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('edit-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->keyBy('id');
        // ['id' => ['id' => '', 'name' => ''], ...]
        $userPermissions = $user->permissions->keyBy('id');
        $permission = Permission::all();
        return view('admin.user.form', compact('user','roles', 'userRoles','permission','userPermissions'));
    }

    public function update($id, Request $request)
    {
        if (auth()->user()->cannot('edit-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $data = $request->all();
        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->roles()->sync($request->input('role',[]));
        $permissions = [];


        foreach($request->input('status',[]) as $permissionId => $value) {

            $permissions[$permissionId] = ['value' => $value];

        }

        $user->permissions()->sync($permissions);


        $user->update($data);

        return redirect()->route('user@edit', $user->id)
            ->with('success', 'Đã cập nhật thông tin User');

    }

    public function delete($id)
    {
        if (auth()->user()->cannot('delete-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user@index')->with('success', 'Đã xoá thành công');
    }


}
