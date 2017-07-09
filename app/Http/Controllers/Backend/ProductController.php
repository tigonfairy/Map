<?php

namespace App\Http\Controllers\Backend;

use Excel;
use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\GroupProduct;
use App\Http\Controllers\Controller;

class ProductController extends AdminController
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
        $group_products = GroupProduct::all();

        return view('admin.product.form', compact('group_products'));
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
                'name' => $request->input('name'),
                'nameEng' => $request->input('nameEng'),
                'parent_id' => $request->input('parent_id'),
                'code' => $request->input('code'),
        ]);
        return redirect()->route('Admin::product@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function edit($id)
    {
        if (auth()->user()->roles->first()['id'] != 1) {
            abort(403);
        }
        $group_products = GroupProduct::all();

        $product = Product::findOrFail($id);
        return view('admin.product.form', compact('product', 'group_products'));
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

        $product->forceFill([
            'name' => $request->input('name'),
            'nameEng' => $request->input('nameEng'),
            'parent_id' => $request->input('parent_id'),
            'code' => $request->input('code'),
        ])->save();

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

    public function importExcel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file'=>'required|max:50000|mimes:xlsx'
        ]);

        if($validator->fails()) {
            $response['status'] = 'fails';
            $response['errors'] = $validator->errors();
        } else {
            $groupProductIds = GroupProduct::pluck('code', 'id')->toArray();

            $file = request()->file('file');
            Excel::load($file,function($reader) use($groupProductIds) {
                $reader->each(function ($sheet) use($groupProductIds) {
                    $product = Product::where('code', $sheet->code)->first();
                    if(count($product) > 0) {
                        $product->forceFill([
                            'name' => $sheet->name_vn ? $sheet->name_vn : '',
                            'nameEng' => $sheet->name_eng ? $sheet->name_eng : '',
                            'code' => $sheet->code ? $sheet->code : '',
                            'parent_id' => '',
                        ])->save();
                    } else {
                        Product::forceCreate([
                            'name' => $sheet->name_vn ? $sheet->name_vn : '',
                            'nameEng' => $sheet->name_eng ? $sheet->name_eng : '',
                            'code' => $sheet->code ? $sheet->code : '',
                            'parent_id' => array_search($sheet->parent_id, $groupProductIds,true) ? array_search($sheet->parent_id, $groupProductIds,true) : 0,
                        ]);
                    }
                });
            });
            flash()->success('Success!', 'Product Supplier successfully updated.');
            $response['status'] = 'success';
        }

        return response()->json($response);
    }
}
