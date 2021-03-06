<?php


namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

use Auth;


class RoleController extends AdminController
{
    public function index(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $roles = Role::orderBy('id')->get();
        return view('admin.role.index',compact('roles'));
    }

    public function add()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $permission = Permission::all();
        return view('admin.role.form', compact('permission'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $this->validate($request,[
            'name' =>'required',
        ]);
        $data = $request->all();
        $role = Role::create($data);
        $role->save();
        $permissions = [];
        foreach($request->input('status',[]) as $permissionId=>$value){
            $permissions[$permissionId] = ['value'=>$value];
        }

        $role->permissions()->sync($permissions);

        return redirect()->route('Admin::role@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $role = Role::findOrFail($id);
        $permission = Permission::all();
        $rolePermissions = $role->permissions->keyBy('id');
        return view('admin.role.form',compact('role','permission','rolePermissions'));
    }

    public function update($id,Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $this->validate($request,[
            'name' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $data = $request->all();

        $permissions = [];
        foreach($request->input('status',[]) as $permissionId=>$value){
            $permissions[$permissionId] = ['value'=>$value];
        }

        $role->permissions()->sync($permissions);


        $role->update($data);


        return redirect()->route('Admin::role@index',$role->id)
            ->with('success', 'Đã cập nhật');
    }

    public function delete($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('Admin::role@index')->with('success', 'Đã xoá thành công');
    }


}
