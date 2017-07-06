<?php

namespace App\Http\Controllers\Backend;

use App\Models\GroupProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupProductController extends AdminController
{
    public function index(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        return view('admin.group_products.index');
    }

    public function add()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }


        return view('admin.group_products.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $this->validate($request,[
            'name' =>'required',
            'code' =>'required',
        ]);

        GroupProduct::forceCreate([
                'name' => $request->input('name'),
                'nameEng' => $request->input('nameEng'),
                'code' => $request->input('code'),
        ]);
        return redirect()->route('Admin::group_product@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function edit($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = GroupProduct::findOrFail($id);
        return view('admin.group_products.form', compact('product'));
    }

    public function update($id, Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = GroupProduct::findOrFail($id);

        $this->validate($request,[
            'name' =>'required',
            'code' =>'required',
        ]);

        $product->forceFill([
            'name' => $request->input('name'),
            'nameEng' => $request->input('nameEng'),
            'code' => $request->input('code'),
        ])->save();

        return redirect()->route('Admin::group_product@index')
            ->with('success', 'Đã cập nhật thông tin Sản phẩm');

    }

    public function delete($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = GroupProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('Admin::group_product@index')->with('success', 'Đã xoá thành công sản phẩm');
    }

    public function getDatatables()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return GroupProduct::getDatatables();
    }
}
