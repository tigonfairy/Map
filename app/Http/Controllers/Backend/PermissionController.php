<?php


namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

use Auth;


class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->cannot('list-permission')) {
            abort(403);
        }
        $permissions = Permission::orderBy('created_at')->get();

        return view('admin.permission.index',compact('permissions'));
    }

    public function add()
    {
        if (auth()->user()->cannot('add-permission')) {
            abort(403);
        }

        return view ('admin.permission.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->cannot('add-permission')) {
            abort(403);
        }

        $this->validate($request,[
            'id'=>'required',
            'description'=>'required',
        ]);

        $data = $request->all();
        $permission = Permission::create($data);
        $permission->save();

        return redirect()->route('Admin::permission@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('edit-permission')) {
            abort(403);
        }

        $permission = Permission::findOrFail($id);
        return view('admin.permission.form',compact('permission'));
    }

    public function update($id,Request $request)
    {
        if (auth()->user()->cannot('edit-permission')) {
            abort(403);
        }

        $this->validate($request, [
            'id' =>'required',
            'description' =>'required',

        ]);
        $permission = Permission::findOrFail($id);
        $data = $request->all();

        $permission->update($data);
        $permission->save();
        return redirect()->route('Admin::permission@index',$permission->id)
            ->with('success', 'Đã cập nhật');
    }

    public function delete($id)
    {
        if (auth()->user()->cannot('delete-permission')) {
            abort(403);
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permission@index')->with('success', 'Đã xoá thành công');
    }



}
