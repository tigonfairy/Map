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

        return view('admin.product.index');
    }

    public function add()
    {

        $group_products = GroupProduct::all();

        return view('admin.product.form', compact('group_products'));
    }

    public function store(Request $request)
    {


        $this->validate($request,[
            'name_vn' =>'required',
        ],[
           'name_vn.required' => 'Vui lòng nhập tên sản phẩm'
        ]);
        $data = $request->all();

        if(isset($data['cbd']) and isset($data['maxgreen']) and isset($data['maxgro']) and empty($data['cbd']) and empty($data['maxgreen']) and empty($data['maxgro']) ) {
            return redirect()->back()->with('error','Vui lòng nhập mã CBD hoặc Maxgreen ,Maxgr0');
        }
        $product = Product::forceCreate([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 0,
            'product_id' => 0
        ]);
        if(isset($data['cbd']) and $data['cbd']) {
            $count = Product::where('code',$data['cbd'])->where('name_code','cbd')->count();
            if($count) {
                return redirect()->back()->with('error','Mã CBD bị trùng');
            }
            Product::forceCreate([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['cbd'],
                'name_code' => 'cbd'
            ]);
        }
        if(isset($data['maxgreen']) and $data['maxgreen']) {
            $count = Product::where('code',$data['maxgreen'])->where('name_code','maxgreen')->count();
            if($count) {
                return redirect()->back()->with('error','Mã maxgreen bị trùng');
            }
            Product::forceCreate([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['maxgreen'],
                'name_code' => 'maxgreen'
            ]);
        }
        if(isset($data['maxgro']) and $data['maxgro']) {
            $count = Product::where('code',$data['maxgro'])->where('name_code','maxgro')->count();
            if($count) {
                return redirect()->back()->with('error','Mã maxgro bị trùng');
            }
            Product::forceCreate([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['maxgro'],
                'name_code' => 'maxgro'
            ]);
        }

        return redirect()->route('Admin::product@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function edit($id)
    {

        $group_products = GroupProduct::all();

        $product = Product::findOrFail($id);

        return view('admin.product.edit', compact('product', 'group_products'));
    }

    public function update($id, Request $request)
    {

        $product = Product::findOrFail($id);

        $this->validate($request,[
            'name_vn' =>'required',
        ]);
        $data = $request->all();
        if(isset($data['cbd']) and isset($data['maxgreen']) and isset($data['maxgro']) and empty($data['cbd']) and empty($data['maxgreen']) and empty($data['maxgro']) ) {
            return redirect()->back()->with('error','Vui lòng nhập mã CBD hoặc Maxgreen ,Maxgr0');
        }


        $product->forceFill([
            'name_vn' => $request->input('name_vn'),
            'name_en' => $request->input('name_en'),
            'parent_id' => $request->input('parent_id'),
            'level' => 0,
            'product_id' => 0
        ])->save();
        if($product->cbd()) {
            $product->cbd()->forceFill([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['cbd'],
            ])->save();
        }
        if($product->maxgro()) {
            $product->maxgro()->forceFill([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['maxgro'],
            ])->save();
        }
        if($product->maxgreen()) {
            $product->maxgreen()->forceFill([
                'name_vn' => $request->input('name_vn'),
                'name_en' => $request->input('name_en'),
                'parent_id' => $request->input('parent_id'),
                'level' => 1,
                'product_id' => $product->id,
                'code' => $data['maxgreen'],
            ])->save();
        }
        return redirect()->route('Admin::product@index')
            ->with('success', 'Đã cập nhật thông tin Sản phẩm');

    }

    public function delete($id)
    {

        $product = Product::findOrFail($id);
        Product::where('product_id',$product->id)->delete();
        $product->delete();
        return redirect()->route('Admin::product@index')->with('success', 'Đã xoá thành công sản phẩm');
    }

    public function getDatatables()
    {

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
