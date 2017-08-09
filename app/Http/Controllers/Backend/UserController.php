<?php

namespace App\Http\Controllers\Backend;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Validator;
use App\Jobs\ImportUser;
class UserController extends AdminController
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
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', [1,2]);
        })->pluck('name', 'id')->all();

        return view('admin.user.form',compact('roles','permission', 'users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['id'] == 3) {
            abort(403);
        }
        Validator::make($request->all(), [
            'name' => 'required',
            'code' =>'required',
            'phone' =>'required',
            'position' =>'required',
            'email' =>'required|email|unique:users',
            'status' =>'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ])->validate();

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
//        $roles = Role::all();
//        $userRoles = $user->roles->keyBy('id');
//        $userPermissions = $user->permissions->keyBy('id');
//        $permission = Permission::all();
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
            'status' =>'required',
            'position' =>'required',
            'phone' =>'required',
        ]);

        $data = $request->all();
        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->roles()->sync($request->input('role',[]));


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
    public function getAccountPosition(Request $request) {
        $position = $request->input('position');
        if(empty($position)) {
            return '';
        }
        if($position == User::NVKD) {
            $users = User::whereIn('position',[User::GSV,User::TV])->get();
        }else {
            $users = User::where('position',$position+1)->get();
        }
        return view('admin.user.get_position',compact('users'));
    }


    public function importExcel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file'=>'required|max:50000|mimes:xlsx,csv'
        ]);

        if($validator->fails()) {
            $response['status'] = 'fails';
            $response['errors'] = $validator->errors();
        } else {

            $file = request()->file('file');
            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/users'), $filename);
            $this->dispatch(new ImportUser( storage_path('app/import/users/' . $filename)));

            flash()->success('Success!', 'User successfully updated.');
            $response['status'] = 'success';
        }

        return response()->json($response);
    }
}
