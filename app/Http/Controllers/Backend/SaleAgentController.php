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

            $totalSales = 0;
            $listProducts = [];
            $capacity = 0;
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::where('agent_id', $agent->id)->where('product_id', $product->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->first();
                            if ($sales) {
                                $slGroup += $sales->sales_real;
                                $capacity = $sales->capacity;
                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sales_real,
                                    'percent' => round($sales->sales_real / $capacity, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }

                    $capacity = $capacity == 0 ? 1 : $capacity;

                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round($slGroup / $capacity, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];

                    $totalSales += $slGroup;
                }
            }

            $capacity = $capacity == 0 ? 1 : $capacity;
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round($totalSales / $capacity, 2),
                'capacity' => $capacity,

            ];

            // table data
            $type = 1;
            $user = $agent->id;

            $table = view('tableDashboard', compact('type', 'user', 'startMonth', 'endMonth'))->render();

            $nvkd = $agent->user;
            $gsv = $nvkd->manager;
            $gdv = $nvkd->manager;

            array_unique($listCodes);

            return response()->json([
                'capacity' => $capacity,
                'user' => $nvkd,
                'gsv' => $gsv,
                'gdv' => $gdv,
                'agents' => $agent,
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
            ]);
        }

        if ($typeSearch == 'gsv') {

            $totalSales = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;

            $user = User::findOrFail($dataSearch);
            $userParentName = $user->manager->name;

            $userOwns = $user->owners()->get();
            $userOwns->push($user);
            $listIds = $userOwns->pluck('id')->toArray();

            $areas = $user->area()->get();

            $locations = [];
            foreach ($areas as $key => $area) {
                foreach ($area->address as $k => $address) {
                    $locations[] = [
                        'border_color' => $area->border_color,
                        'background_color' => $area->background_color,
                        'area' => $address
                    ];
                }
            }

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

            foreach ($agents as $agent) {

                $sales = SaleAgent::where('agent_id', $agent->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->get();
                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;
                    $capacity = $sale->capacity;
                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round($saleAgents / $capacity, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }

            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round($totalSales / $capacity, 2),
                'capacity' => $capacity
            ];

            $agentIds = $agents->pluck('id')->toArray();
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;

                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round($sales->sum / $capacity, 2),
                                    'capacity' => $capacity
                                ];

                                $listCodes[] = $product->code;
                            }
                        }
                    }

                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round($slGroup / $capacity, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }

            // table data
            $type = 2;
            $id = $user->id;

            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            array_unique($listCodes);
            return response()->json([
                'user' => $user,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round($totalSales / $capacity, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
            ]);
        }

        if ($typeSearch == 'tv') {

            $totalSales = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;

            $userTv = User::findOrFail($dataSearch);
            $userParentName = $userTv->manager->name;

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

            $areas = $userTv->area()->get();

            $locations = [];
            foreach ($areas as $key => $area) {
                foreach ($area->address as $k => $address) {
                    $locations[] = [
                        'border_color' => $area->border_color,
                        'background_color' => $area->background_color,
                        'area' => $address
                    ];
                }
            }

            $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

            foreach ($agents as $agent) {

                $sales = SaleAgent::where('agent_id', $agent->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->get();

                foreach ($sales as $sale) {
                    $saleAgents += $sale->sales_real;
                    $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                }
                $capacity = $capacity == 0 ? 1 : $capacity;
                $listAgents[] = [
                    'agent' => $agent,
                    'totalSales' => $saleAgents,
                    'capacity' => $capacity,
                    'percent' => round($saleAgents / $capacity, 2)
                ];
                $totalSales += $saleAgents;
                $saleAgents = 0;
            }

            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSales,
                'percent' => round($totalSales / $capacity, 2),
                'capacity' => $capacity
            ];

            $agentIds = $agents->pluck('id')->toArray();
            $listCodes = [];
            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();

            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;

                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round($sales->sum / $capacity, 2),
                                    'capacity' => $capacity
                                ];
                                $listCodes[] = $product->code;
                            }
                        }
                    }

                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round($slGroup / $capacity, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }

            // table data
            $type = 3;
            $id = $userTv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            array_unique($listCodes);

            return response()->json([
                'user' => $userTv,
                'director' => $userParentName,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSales,
                'capacity' => $capacity,
                'percent' => round($totalSales / $capacity, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
            ]);
        }

        if ($typeSearch == 'gdv') {
            $totalSaleGSV = 0;
            $totalSaleGDV = 0;
            $saleAgents = 0;
            $listAgents = [];
            $capacity = 0;
            $data = [];
            $dataGdv = [];
            $locations = [];
            $agentIds = [];
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

                $agents = Agent::whereIn('manager_id', $listIds)->with('user')->get();

                foreach ($agents->pluck('id')->toArray() as $agentId) {
                    $agentIds[] = $agentId;
                }

                foreach ($agents as $agent) {

                    $sales = SaleAgent::where('agent_id', $agent->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->get();
                    foreach ($sales as $sale) {
                        $saleAgents += $sale->sales_real;
                        $capacity = isset($sale->capacity) ?  $sale->capacity : 1;
                    }
                    $capacity = $capacity == 0 ? 1 : $capacity;
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
                    'gsv' => $user,
                    'agents' => $agents,
                    'totalSales' => $totalSaleGSV,
                    'capacity' => $capacity,
                    'percent' => round($totalSaleGSV / $capacity, 2)
                ];
                $totalSaleGSV = 0;
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

            $agents = Agent::where('manager_id', $userGdv->id)->with('user')->get();
            if (count($agents) > 0) {
                foreach ($agents as $agent) {
                    $sales = SaleAgent::where('agent_id', $agent->id)->where('month','>=',$startMonth)->where('month','<=',$endMonth)->select('sales_real', 'capacity')->get();
                    $saleAgents = 0;
                    foreach ($sales as $sale) {
                        $saleAgents += $sale->sales_real;
                        $capacity = isset($sale->capacity) ? $sale->capacity : 1;
                    }
                    $capacity = $capacity == 0 ? 1 : $capacity;
                    $agent->totalSales = $saleAgents;
                    $agent->capacity = $capacity;
                    $agent->percent = round($saleAgents / $capacity, 2);

                    $dataGdv[] = [
                        'gsv' => $userGdv,
                        'agents' => $agent,
                        'totalSales' => $saleAgents,
                        'capacity' => $capacity,
                        'percent' => round($saleAgents / $capacity, 2)
                    ];
                    $totalSaleGDV += $saleAgents;
                }
            }

            foreach ($agents->pluck('id')->toArray() as $agentId) {
                $agentIds[] = $agentId;
            }

            // xử lý product
            $listProducts[] = [
                'id' => 0,
                'name' => 'Tổng sản lượng',
                'code' => 'Tổng sản lượng',
                'totalSales' => $totalSaleGDV,
                'percent' => round($totalSaleGDV / $capacity, 2),
                'capacity' => $capacity
            ];

            $groupProduct = \App\Models\GroupProduct::orderBy('created_at','desc')->get();
            $listCodes = [];
            if (count($groupProduct) > 0) {
                foreach ($groupProduct as $group) {
                    $array = [];
                    $slGroup = 0;
                    $products = $group->product()->where('level',1)->orderBy('created_at','desc')->get();
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $sales = SaleAgent::join('products', 'sale_agents.product_id', '=', 'products.id')->whereIn('agent_id', $agentIds)->where('month','>=',$startMonth)->where('month','<=',$endMonth)
                                ->where('sale_agents.product_id', $product->id)->selectRaw('sum(sales_real) as sum, sale_agents.product_id, products.name_vn, products.code')->first();
                            if ($sales) {
                                $slGroup += $sales->sum;

                                $array[] = [
                                    'id' => $product->id,
                                    'name' => $product->code,
                                    'code' => $product->code,
                                    'totalSales' => $sales->sum,
                                    'percent' => round($sales->sum / $capacity, 2),
                                    'capacity' => $capacity
                                ];

                                $listCodes[] = $product->code;
                            }
                        }
                    }

                    $listProducts[] = [
                        'id' => $group->id,
                        'name' => $group->name_vn,
                        'code' => $group->name_vn,
                        'totalSales' => $slGroup,
                        'percent' => round($slGroup / $capacity, 2),
                        'capacity' => $capacity,
                        'listProducts' => $array,
                    ];
                }
            }

            // table data
            $type = 4;
            $id = $userGdv->id;
            $table = view('tableDashboard', compact('type', 'id', 'startMonth', 'endMonth'))->render();

            array_unique($listCodes);

            return response()->json([
                'user' => $userGdv,
                'result' => $data,
                'resultGdv' => $dataGdv,
                'locations' => $locations,
                'listAgents' => $listAgents,
                'totalSales' => $totalSaleGDV,
                'capacity' => $capacity,
                'percent' => round($totalSaleGDV / $capacity, 2),
                'listProducts' => $listProducts,
                'table' => $table,
                'listCodes' => $listCodes
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
