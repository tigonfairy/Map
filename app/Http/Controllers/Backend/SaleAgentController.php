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
use DB;

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
                'sales_plan' => intval($sales_plan),
                'sales_real' => $sales_real[$key] ? intval($sales_real[$key]) : 0,
                'capacity' => intval($capacity)
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
                           'capacity' => intval($capacity),
                            'sales_plan' => intval($sales_plan),
                            'sales_real' => (isset($sales_real[$key])) ? intval($sales_real[$key]) : 0
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
            $this->dispatch(new ImportDataAgent( storage_path('app/import/products/' . $filename),$month,$name,auth()->user()->id));

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
            $this->dispatch(new ExportAgent( $startMonth,$endMonth,auth()->user()->id));
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
        $this->dispatch(new ExportTD( $startMonth,$endMonth,$startTD,$endTD,$type,auth()->user()->id));
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

            // xu ly filter
            $totalSales = 0;
            $totalCBD = 0;
            $totalMaxGreen = 0;
            $totalMaxGro = 0;
            $listProducts = [];
            $listGroups = [];
            $listTotals = [];
            $capacity = 0;
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::where('agent_id', $agent->id)->where('product_id', $product->id)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)->select(DB::raw("SUM(sales_real) as sales_real"), "capacity")->first();
                            if (!is_null($sales->sales_real)) {
                                $slGroup += $sales->sales_real;
                                $capacity = $sales->capacity;

                                $listProducts[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sales_real,
                                    'capacity' => $capacity,
                                    'type' => 'product'
                                ];

                                if ($product->name_code == 'cbd') {
                                    $totalCBD += $sales->sales_real;
                                } else if($product->name_code == 'maxgreen') {
                                    $totalMaxGreen += $sales->sales_real;
                                } else if($product->name_code == 'maxgro') {
                                    $totalMaxGro += $sales->sales_real;
                                }
                            }


                        }
                    }

                    $listGroups[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'capacity' => $capacity,
                        'listProducts' => $array,
                        'type' => 'group'
                    ];

                    $totalSales += $slGroup;
                }
            }

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng CBD',
                'code' => 'Tổng sản lượng CBD',
                'totalSales' => $totalCBD,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGreen',
                'code' => 'Tổng sản lượng MaxGreen',
                'totalSales' => $totalMaxGreen,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGro',
                'code' => 'Tổng sản lượng MaxGro',
                'totalSales' => $totalMaxGro,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            return response()->json([
                'table' => $table,
                'listProducts' => $listProducts,
                'listGroups' => $listGroups,
                'listTotals' => $listTotals,
            ]);
        }

        if ($typeSearch == 'nvkd' || $typeSearch == '') {

            if ($dataSearch != 0) {
                $user = User::findOrFail($dataSearch);
            } else {
                $user = auth()->user();
            }

            // table data
            $type = 5;
            $id = $user->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            // xu ly filter
            $agentIds = Agent::where('manager_id', $user->id)->with('user')->pluck('id')->all();
            $totalSales = 0;
            $totalCBD = 0;
            $totalMaxGreen = 0;
            $totalMaxGro = 0;
            $listProducts = [];
            $listGroups = [];
            $listTotals = [];
            $capacity = 0;
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.capacity ')->first();
                            if (!is_null($sales->sum)) {
                                $slGroup += $sales->sum;
                                $capacity = $sales->capacity;
                                $listProducts[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'capacity' => $capacity
                                ];

                                if ($product->name_code == 'cbd') {
                                    $totalCBD += $sales->sum;
                                } else if($product->name_code == 'maxgreen') {
                                    $totalMaxGreen += $sales->sum;
                                } else if($product->name_code == 'maxgro') {
                                    $totalMaxGro += $sales->sum;
                                }
                            }
                        }
                    }

                    $listGroups[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'capacity' => $capacity,
                    ];
                    $totalSales += $slGroup;
                }
            }

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng CBD',
                'code' => 'Tổng sản lượng CBD',
                'totalSales' => $totalCBD,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGreen',
                'code' => 'Tổng sản lượng MaxGreen',
                'totalSales' => $totalMaxGreen,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGro',
                'code' => 'Tổng sản lượng MaxGro',
                'totalSales' => $totalMaxGro,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            return response()->json([
                'table' => $table,
                'listProducts' => $listProducts,
                'listGroups' => $listGroups,
                'listTotals' => $listTotals,
            ]);
        }

        if ($typeSearch == 'gsv') {

            $user = User::findOrFail($dataSearch);

            // table data
            $type = 2;
            $id = $user->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            // xu ly filter
            $userOwns = $user->owners()->get();
            $userOwns->push($user);
            $listIds = $userOwns->pluck('id')->toArray();
            $agentIds = Agent::whereIn('manager_id', $listIds)->pluck('id')->all();

            $totalSales = 0;
            $totalCBD = 0;
            $totalMaxGreen = 0;
            $totalMaxGro = 0;
            $listProducts = [];
            $listGroups = [];
            $listTotals = [];
            $capacity = 0;
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.capacity ')->first();
                            if (!is_null($sales->sum)) {
                                $slGroup += $sales->sum;
                                $capacity = $sales->capacity;
                                $listProducts[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'capacity' => $capacity
                                ];

                                if ($product->name_code == 'cbd') {
                                    $totalCBD += $sales->sum;
                                } else if($product->name_code == 'maxgreen') {
                                    $totalMaxGreen += $sales->sum;
                                } else if($product->name_code == 'maxgro') {
                                    $totalMaxGro += $sales->sum;
                                }
                            }
                        }
                    }

                    $listGroups[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'capacity' => $capacity,
                    ];
                    $totalSales += $slGroup;
                }
            }

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng CBD',
                'code' => 'Tổng sản lượng CBD',
                'totalSales' => $totalCBD,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGreen',
                'code' => 'Tổng sản lượng MaxGreen',
                'totalSales' => $totalMaxGreen,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGro',
                'code' => 'Tổng sản lượng MaxGro',
                'totalSales' => $totalMaxGro,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            return response()->json([
                'table' => $table,
                'listProducts' => $listProducts,
                'listGroups' => $listGroups,
                'listTotals' => $listTotals,
            ]);
        }

        if ($typeSearch == 'tv') {


            $userTv = User::findOrFail($dataSearch);


            // table data
            $type = 3;
            $id = $userTv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            // xu ly filter
            $userTv = User::findOrFail($dataSearch);
            $userOwns = $userTv->owners()->get();
            foreach ($userOwns as $u) {
                if (count($u->owners) > 0) {
                    foreach ($u->owners as $us) {
                        $userOwns->push($us);
                    }
                } else {
                    $userOwns->push($u);
                }
            }
            $userOwns->push($userTv);

            $listIds = $userOwns->pluck('id')->toArray();
            $agentIds = Agent::whereIn('manager_id', $listIds)->pluck('id')->all();

            $totalSales = 0;
            $totalCBD = 0;
            $totalMaxGreen = 0;
            $totalMaxGro = 0;
            $listProducts = [];
            $listGroups = [];
            $listTotals = [];
            $capacity = 0;
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.capacity ')->first();
                            if (!is_null($sales->sum)) {
                                $slGroup += $sales->sum;
                                $capacity = $sales->capacity;
                                $listProducts[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'capacity' => $capacity
                                ];

                                if ($product->name_code == 'cbd') {
                                    $totalCBD += $sales->sum;
                                } else if($product->name_code == 'maxgreen') {
                                    $totalMaxGreen += $sales->sum;
                                } else if($product->name_code == 'maxgro') {
                                    $totalMaxGro += $sales->sum;
                                }
                            }
                        }
                    }

                    $listGroups[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'capacity' => $capacity,
                    ];
                    $totalSales += $slGroup;
                }
            }

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng CBD',
                'code' => 'Tổng sản lượng CBD',
                'totalSales' => $totalCBD,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGreen',
                'code' => 'Tổng sản lượng MaxGreen',
                'totalSales' => $totalMaxGreen,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGro',
                'code' => 'Tổng sản lượng MaxGro',
                'totalSales' => $totalMaxGro,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            return response()->json([
                'table' => $table,
                'listProducts' => $listProducts,
                'listGroups' => $listGroups,
                'listTotals' => $listTotals,
            ]);
        }

        if ($typeSearch == 'gdv') {

            $userGdv = User::findOrFail($dataSearch);

            // table data
            $type = 4;
            $id = $userGdv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            // xu ly filter
            $userGdv = User::findOrFail($dataSearch);
            $userGSV = $userGdv->owners()->get();

            foreach ($userGSV as $user) {
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
            }
            $listIds[] = $userGdv->id;

            $agentIds = Agent::whereIn('manager_id', $listIds)->pluck('id')->all();

            $totalSales = 0;
            $totalCBD = 0;
            $totalMaxGreen = 0;
            $totalMaxGro = 0;
            $listProducts = [];
            $listGroups = [];
            $listTotals = [];
            $capacity = 0;
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month', '>=', $startMonth)->where('month', '<=', $endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.capacity ')->first();
                            if (!is_null($sales->sum)) {
                                $slGroup += $sales->sum;
                                $capacity = $sales->capacity;
                                $listProducts[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'capacity' => $capacity
                                ];

                                if ($product->name_code == 'cbd') {
                                    $totalCBD += $sales->sum;
                                } else if($product->name_code == 'maxgreen') {
                                    $totalMaxGreen += $sales->sum;
                                } else if($product->name_code == 'maxgro') {
                                    $totalMaxGro += $sales->sum;
                                }
                            }
                        }
                    }

                    $listGroups[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'capacity' => $capacity,
                    ];
                    $totalSales += $slGroup;
                }
            }

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng CBD',
                'code' => 'Tổng sản lượng CBD',
                'totalSales' => $totalCBD,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGreen',
                'code' => 'Tổng sản lượng MaxGreen',
                'totalSales' => $totalMaxGreen,
                'capacity' => $capacity,
                'type' => 'total'
            ];
            $listTotals[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng MaxGro',
                'code' => 'Tổng sản lượng MaxGro',
                'totalSales' => $totalMaxGro,
                'capacity' => $capacity,
                'type' => 'total'
            ];

            return response()->json([
                'table' => $table,
                'listProducts' => $listProducts,
                'listGroups' => $listGroups,
                'listTotals' => $listTotals,
            ]);
        }
    }

    public function matrixFilter(Request $request) {
        $data = $request->all();
        $type = $request->input('type_data_search');
        $manager_id = $request->input('data_search');

        if ($request->input('typeSearch') == '') {
            $manager_id = auth()->user()->id;
            $type = 5;
        }
        $startMonth = $request->input('startMonth');
        $endMonth = $request->input('endMonth');
        return view('admin.saleAgent.matrix',compact('type','manager_id','startMonth','endMonth'))->render();
    }

}
