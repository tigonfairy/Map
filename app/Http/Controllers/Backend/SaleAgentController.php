<?php

namespace App\Http\Controllers\Backend;


use App\Models\Agent;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Jobs\ImportDataAgent;
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
        if($role->id == 1) {
            $agents = Agent::all();

        } else {
            $userOwns = $user->manager()->get();
            $userOwns->push($user);
            $managerIds = $userOwns->pluck('id')->toArray();
            $agents = Agent::whereIn('manager_id', $managerIds)->get();
        }
        $products = Product::all();

        return view('admin.saleAgent.form',compact('agents', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate(request(),[
            'agent_id' => 'required',
            'month' => 'required',
        ],[
            'agent_id.required' => 'Vui lòng chọn đại lý',
            'month.required' => 'Vui lòng chọn thời gian',
        ]);

        $product_ids = request('product_id');
        $sales_plan = request('sales_plan');
        $sales_real = request('sales_real');

        foreach ($product_ids as $key => $product_id) {
            SaleAgent::firstOrCreate([
                'agent_id' => request('agent_id'),
                'product_id' => $product_id,
                'month' => request('month'),
                'sales_plan' => $sales_plan[$key] ? $sales_plan[$key] : 0,
                'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
            ]);
        }

        return redirect()->route('Admin::map@listAgency')->with('success','Tạo dữ liệu cho đại lý thành công');
    }

    public function edit($agentId, $month)
    {
        $saleAgent = SaleAgent::where('agent_id',$agentId)->where('month',$month)->get();
        $products = Product::all();
        $agents = Agent::all();

        return view('admin.saleAgent.form', compact('saleAgent', 'products', 'agents'));
    }

    public function update($agentId)
    {
        $this->validate(request(),[
            'month' => 'required',
        ],[
            'month.required' => 'Vui lòng chọn thời gian',
        ]);

        $product_ids = request('product_id');
        $sales_plan = request('sales_plan');
        $sales_real = request('sales_real');

        $saleAgentCount = SaleAgent::where('agent_id',$agentId)->where('month',request('month'))->count();

        if ($saleAgentCount > 0) {
            foreach ($product_ids as $key => $product_id) {
                $saleAgent =  SaleAgent::where('agent_id',$agentId)->where('month',request('month'))->where('product_id',$product_id)->first();
                if($saleAgent) {
                    $saleAgent->update([
                        'sales_plan' => $sales_plan[$key] ? $sales_plan[$key] : 0,
                        'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
                    ]);
                } else {
                    SaleAgent::firstOrCreate([
                        'agent_id' => $agentId,
                        'product_id' => $product_id,
                        'month' => request('month'),
                        'sales_plan' => $sales_plan[$key] ? $sales_plan[$key] : 0,
                        'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
                    ]);
                }
            }
        } else {
            foreach ($product_ids as $key => $product_id) {
                SaleAgent::firstOrCreate([
                    'agent_id' => $agentId,
                    'product_id' => $product_id,
                    'month' => request('month'),
                    'sales_plan' => $sales_plan[$key] ? $sales_plan[$key] : 0,
                    'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
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
            $month = $request->input('month');
            $file = request()->file('file');
            $filename = $month.'_'.time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/products'), $filename);
            $this->dispatch(new ImportDataAgent( storage_path('app/import/products/' . $filename)),$month);

            flash()->success('Success!', 'Data successfully updated.');
            $response['status'] = 'success';
        }

        return response()->json($response);
    }
}
