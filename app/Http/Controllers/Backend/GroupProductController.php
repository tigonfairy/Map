<?php

namespace App\Http\Controllers\Backend;

use App\Models\GroupProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupProductController extends AdminController
{
    public function index(Request $request)
    {


        return view('admin.group_products.index');
    }

    public function add()
    {



        return view('admin.group_products.form');
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'name_vn' =>'required',
            'code' =>'required',
        ]);

        GroupProduct::forceCreate([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'code' => $request->input('code'),
        ]);
        return redirect()->route('Admin::group_product@index')
            ->with('success', 'Đã thêm nhóm sản phẩm');
    }

    public function edit($id)
    {

        $product = GroupProduct::findOrFail($id);
        return view('admin.group_products.form', compact('product'));
    }

    public function update($id, Request $request)
    {


        $product = GroupProduct::findOrFail($id);

        $this->validate($request,[
            'name_vn' =>'required',
            'code' =>'required',
        ]);

        $product->forceFill([
            'name_vn' => $request->input('name_vn'),
            'name_en' => $request->input('name_en'),
            'code' => $request->input('code'),
        ])->save();

        return redirect()->route('Admin::group_product@index')
            ->with('success', 'Đã cập nhật thông tin nhóm Sản phẩm');

    }

    public function delete($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = GroupProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('Admin::group_product@index')->with('success', 'Đã xoá thành công nhóm  sản phẩm');
    }

    public function getDatatables()
    {

        return GroupProduct::getDatatables();
    }
}
