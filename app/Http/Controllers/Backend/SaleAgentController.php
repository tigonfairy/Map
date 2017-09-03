<?php

namespace App\Http\Controllers\Backend;


use App\Jobs\ExportAgent;
use App\Models\Agent;
use App\Models\User;
use App\Models\Product;
use App\Models\SaleAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Jobs\ImportDataAgent;
use App\Jobs\ExportTD;
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
        SaleAgent::where('agent_id', request('agent_id'))->where('month',request('month'))->delete();
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

        SaleAgent::where('agent_id',$agentId)->where('month',request('month'))->delete();

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
       $this->validate($request, [
            'startMonth' => 'required',
            'endMonth' => 'required'
        ]);

            $startMonth = $request->input('startMonth');
            $endMonth = $request->input('endMonth');
            $this->dispatch(new ExportAgent( $startMonth,$endMonth));
            return redirect()->back()->with('success','Export trong quá trình chạy.Vui lòng chờ thông báo để tải file');



    }
    public function exportTienDo(Request $request) {
        $this->validate($request, [
            'startMonth' => 'required',
            'endMonth' => 'required',
            'type' => 'required'
        ]);
        $startMonth = $request->input('startMonth');
        $endMonth = $request->input('endMonth');
        $year = substr($startMonth,3,7);
        $type = $request->input('type');
        $startTD = $startMonth;
        $endTD = $endMonth;

        if($type == 1) {
            if($endMonth <= '03-'.$year ) {
                $startTD = '01-'.$year;
                $endTD = '03-'.$year;
            }
            if($endMonth <= '06-'.$year ) {
                $startTD = '04-'.$year;
                $endTD = '06-'.$year;
            }
            if($endMonth <= '09-'.$year ) {
                $startTD = '07-'.$year;
                $endTD = '09-'.$year;
            }
            if($endMonth <= '12-'.$year ) {
                $startTD = '10-'.$year;
                $endTD = '12-'.$year;
            }
        }
        if($type== 2) {
            if($endMonth <= '06-'.$year ) {
                $startTD = '01-'.$year;
                $endTD = '06-'.$year;
            }
            if($endMonth <= '12-'.$year ) {
                $startTD = '07-'.$year;
                $endTD = '12-'.$year;
            }
        }
        if($type == 3) {
            $startTD = '01-'.$year;
            $endTD = '12-'.$year;
        }
        $this->dispatch(new ExportTD( $startMonth,$endMonth,$startTD,$endTD,$type));
        return redirect()->back()->with('success','Export tiến độ trong quá trình chạy.Vui lòng chờ thông báo để tải file');

    }

    public function filter()
    {
        $user = auth()->user();
        return view('admin.saleAgent.filter', compact('user'));
    }

    public function dataFilter(Request $request)
    {

        $typeSearch =$request->input('type_search');
        $dataSearch = $request->has('data_search') ? $request->input('data_search') : 0;
        $startMonth = $request->input('startMonth');
        $endMonth = $request->input('endMonth');

        if ($typeSearch == 'agents') {

            $agent = Agent::findOrFail($dataSearch);

            // table data
            $type = 1;
            $user = $agent->id;

            $table = view('tableDashboard', compact('type', 'user', 'startMonth', 'endMonth'))->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        if ($typeSearch == 'gsv') {
            $user = User::findOrFail($dataSearch);

            // table data
            $type = 2;
            $id = $user->id;

            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        if ($typeSearch == 'tv') {


            $userTv = User::findOrFail($dataSearch);

            // table data
            $type = 3;
            $id = $userTv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        if ($typeSearch == 'gdv') {
            $userGdv = User::findOrFail($dataSearch);

            // table data
            $type = 4;
            $id = $userGdv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            return response()->json([
                'table' => $table,
            ]);
        }

        if ($typeSearch == 'admin') {
            $userGDVs = User::where('position', User::GĐV)->get();
            $data = [];
            $locations = [];

            foreach ($userGDVs as $gdv) {
                $totalSaleGDV = 0;
                $listAgents = [];
                foreach ($gdv->owners as $user) {
                    $totalSaleGSV = 0;
                    if ($user->position == User::GSV) { // gsv
                        if (count($user->owners) > 0) {
                            foreach ($user->owners as $u1) {
                                $listIds[] = $u1->id;
                            }
                        }
                    } else if ($user->position == User::TV) {
                        if (count($user->owners) > 0) {
                            foreach ($user->owners as $u2) {
                                if (count($u2->owners) > 0) {
                                    foreach ($u2->owners as $u3) {
                                        $listIds[] = $u3->id;
                                    }
                                }
                                $listIds[] = $u2->id;
                            }
                        }
                    }
                    $listIds[] = $user->id;

                    // area
                    $areas = $user->area()->get();

                    foreach ($areas as $key => $area) {
                        foreach ($area->address as $k => $address) {
                            $locations[] = [
                                'border_color' => $area->border_color,
                                'background_color' => $area->background_color,
                                'area' => $address
                            ];
                        }
                    }
                }

                $listIds[] = $gdv->id;
                $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();
                $saleAgents = 0;
                foreach ($agents as $agent) {
                    $sales = SaleAgent::where('agent_id', $agent->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->get();

                    foreach ($sales as $sale) {
                        $saleAgents += $sale->sales_real;
                        $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                    }
                    $capacity = isset($capacity) ? $capacity : 1;
                    $listAgents[] = [
                        'agent' => $agent,
                        'totalSales' => $saleAgents,
                        'capacity' => $capacity,
                        'percent' => round($saleAgents / $capacity, 2)
                    ];

                    $totalSaleGSV += $saleAgents;
                    $agent->totalSales = $saleAgents;
                    $agent->capacity = $capacity;
                    $agent->percent = round($saleAgents / $capacity, 2);
                    $saleAgents = 0;
                }
                $listIds = [];
                $totalSaleGDV += $totalSaleGSV;

                $data[] = [
                    'gdv' => $gdv,
                    'agents' => $agents,
                    'totalSales' => $totalSaleGDV,
                    'capacity' => $capacity,
                    'percent' => round($totalSaleGDV / $capacity, 2)
                ];

            }

            return response()->json([
                'result' => $data,
                'locations' => $locations,
            ]);

        }
    }
}
