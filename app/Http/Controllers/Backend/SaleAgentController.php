<?php

namespace App\Http\Controllers\Backend;


use App\Models\Agent;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleAgentController extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->cannot('list-saleAgent')) {
            abort(403);
        }
        return view('admin.saleAgent.index');
    }

    public function add()
    {

        if (auth()->user()->cannot('add-saleAgent')) {
            abort(403);
        }

        $agents = Agent::all();
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
            SaleAgent::create([
                'agent_id' => request('agent_id'),
                'product_id' => $product_id,
                'month' => request('month'),
                'sales_plan' => $sales_plan[$key] ? $sales_plan[$key] : 0,
                'sales_real' => $sales_real[$key] ? $sales_real[$key] : 0,
            ]);
        }

        return redirect()->route('Admin::saleAgent@index')->with('success','Tạo dữ liệu cho đại lý thành công');
    }

    public function edit($agentId, $month)
    {
        if (auth()->user()->cannot('edit-saleAgent')) {
            abort(403);
        }

        $saleAgent = SaleAgent::where('agent_id',$agentId)->where('month',$month)->get();
        $products = Product::all();
        $agents = Agent::all();

        return view('admin.saleAgent.form', compact('saleAgent', 'products', 'agents'));
    }

    public function update($agentId)
    {
        if (auth()->user()->cannot('edit-saleAgent')) {
            abort(403);
        }

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
                    SaleAgent::create([
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
                SaleAgent::create([
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
        if (auth()->user()->cannot('delete-saleAgent')) {
            abort(403);
        }

        SaleAgent::where('agent_id',$agentId)->where('month',$month)->delete();
        return redirect()->route('Admin::saleAgent@index')->with('success', 'Đã xoá thành công');
    }

    public function getDatatables()
    {
        return SaleAgent::getDatatables();
    }
}
