<?php

namespace App\Http\Controllers\Backend;


use App\Jobs\ExportAgent;
use App\Models\Agent;
use App\Models\GroupProduct;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Jobs\ImportDataAgent;
use Excel;
class SaleAgentController extends AdminController
{

    public function index(Request $request)
    {
        return view('admin.saleAgent.index');
    }

    public function add()
    {
        $user = auth()->user();
        $role = $user->roles()->first();

            $agents = Agent::all();


//            $userOwns = $user->manager()->get();
//            $userOwns->push($user);
//            $managerIds = $userOwns->pluck('id')->toArray();
//            $agents = Agent::whereIn('manager_id', $managerIds)->get();
//
        $products = Product::where('level',1)->get();

        return view('admin.saleAgent.form',compact('agents', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate(request(),[
            'month' => 'required',
            'capacity' => 'required'
        ],[
            'agent_id.required' => 'Vui lòng chọn đại lý',
            'month.required' => 'Vui lòng chọn thời gian',
        ]);

        $product_ids = request('product_id');
        $capacity = request('capacity',0);
        $sales_plan = request('sales_plan',0);
        $sales_real = request('sales_real');

        foreach ($product_ids as $key => $product_id) {
            $agent = SaleAgent::firstOrCreate([
                'agent_id' => request('agent_id'),
                'product_id' => $product_id,
                'month' => request('month'),
            ]);
            $agent->update([
                'sales_plan' => $sales_plan,
                'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
                'capacity' => $capacity
            ]);
        }

        return redirect()->route('Admin::saleAgent@index')->with('success','Tạo dữ liệu cho đại lý thành công');
    }

    public function edit($agentId, $month)
    {
        $saleAgent = SaleAgent::where('agent_id',$agentId)->where('month',$month)->get();
        $products = Product::where('level',1)->get();
        $agents = Agent::all();
        return view('admin.saleAgent.form', compact('saleAgent', 'products', 'agents'));
    }

    public function update($agentId,Request $request)
    {
        Validator::make($request->all(), [
            'month' => 'required',
            'capacity' => 'required'
        ])->validate();


        $product_ids = request('product_id');
        $capacity = request('capacity');
        $sales_plan = request('sales_plan');
        $sales_real = request('sales_real');



        if (count($sales_real)) {
            foreach ($product_ids as $key => $product_id) {
                    $sale = SaleAgent::firstOrCreate([
                        'agent_id' => $agentId,
                        'product_id' => $product_id,
                        'month' => request('month'),
                    ]);
                        $sale->update([
                           'capacity' => $capacity,
                            'sales_plan' => $sales_plan,
                            'sales_real' => (isset($sales_real[$key])) ? $sales_real[$key] : 0
                        ]);
            }
        }

        return redirect()->route('Admin::saleAgent@index')
            ->with('success', 'Đã cập nhật dữ liệu đại lý thành công');
    }

    public function delete($agentId,$month)
    {
        SaleAgent::where('agent_id',$agentId)->where('month',$month)->delete();
        return redirect()->route('Admin::saleAgent@index')->with('success', 'Đã xoá thành công');
    }

    public function getDatatables()
    {
        return SaleAgent::getDatatables();
    }
    public function importExcelDataAgent(Request $request) {
        $validator = Validator::make($request->all(), [
            'file'=>'required|max:50000|mimes:xlsx,csv',
            'month' => 'required'
        ]);

        if($validator->fails()) {
            $response['status'] = 'fails';
            $response['errors'] = $validator->errors();
        } else {
            $name =  $request->file('file')->getClientOriginalName();
            $month = $request->input('month');
            $file = request()->file('file');
            $filename = $month.'_'.time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/products'), $filename);
            $this->dispatch(new ImportDataAgent( storage_path('app/import/products/' . $filename),$month,$name));

            flash()->success('Success!', 'Data successfully updated.');
            $response['status'] = 'success';
        }

        return response()->json($response);
    }
    public function exportExcelDataAgent(Request $request) {
        $validator = Validator::make($request->all(), [
            'startMonth' => 'required',
            'endMonth' => 'required'
        ]);
        if($validator->fails()) {
            $response['status'] = 'fails';
            $response['errors'] = $validator->errors();
        } else {
            $startMonth = $request->input('startMonth');
            $endMonth = $request->input('endMonth');
            $groupProduct = GroupProduct::orderBy('created_at','desc')->get();
            return view('exportExcel',compact('groupProduct','startMonth','endMonth'));
//            $this->dispatch(new ExportAgent( $startMonth,$endMonth));
//            return redirect()->back()->with('success','Export trong quá trình chạy.Vui lòng chờ thông báo để tải file');
        }


    }
}
