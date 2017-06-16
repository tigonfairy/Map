<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        return view('admin.product.index');
    }

    public function add()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return view('admin.product.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $this->validate($request,[
            'name' =>'required',
        ]);

        Product::forceCreate([
                'name' => $request->input('name')
        ]);
        return redirect()->route('Admin::product@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function edit($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = Product::findOrFail($id);
        return view('admin.product.form', compact('product'));
    }

    public function update($id, Request $request)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = Product::findOrFail($id);

        $this->validate($request,[
            'name' =>'required',
        ]);

        $data = $request->all();
        $product->update($data);

        return redirect()->route('Admin::product@index')
            ->with('success', 'Đã cập nhật thông tin Sản phẩm');

    }

    public function delete($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }

        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('Admin::product@index')->with('success', 'Đã xoá thành công sản phẩm');
    }

    public function getDatatables()
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        return Product::getDatatables();
    }
}
